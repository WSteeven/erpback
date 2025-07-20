<?php

namespace App\Http\Controllers\Seguridad;

use App\Exports\Seguridad\AlimentacionGuardias\ReporteAlimentacionGuardiasExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Seguridad\ReporteAlimentacionRequest;
use App\Models\Seguridad\Bitacora;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Src\App\FondosRotativos\ReportePdfExcelService;

class ReporteAlimentacionController extends Controller
{
    private ReportePdfExcelService $reporteService;

    public function index(ReporteAlimentacionRequest $request): JsonResponse|\Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        $this->reporteService = new ReportePdfExcelService();

        $tipo = $request->accion ?? 'consulta';
        $fecha_inicio = Carbon::parse($request->fecha_inicio)->format('Y-m-d');
        $fecha_fin = Carbon::parse($request->fecha_fin)->format('Y-m-d');

        $bitacoras = Bitacora::with(['zona', 'agenteTurno'])
            ->whereBetween('created_at', [$fecha_inicio, $fecha_fin])
            ->when($request->empleado, fn($q) => $q->where('agente_turno_id', $request->empleado))
            ->when($request->zona, fn($q) => $q->where('zona_id', $request->zona))
            ->when($request->jornada, fn($q) => $q->where('jornada', $request->jornada))
            ->get();

        if ($bitacoras->isEmpty() && $tipo === 'consulta') {
            return response()->json(['message' => 'No se encontraron registros para los filtros ingresados.'], 404);
        }

        $agente = $bitacoras->first()?->agenteTurno;
        $guardia = $agente ? trim("{$agente->nombres} {$agente->apellidos}") : '-';

        $detalle = $this->mapearListado($bitacoras);
        $monto_total = $detalle->sum('monto');

        $reporte = compact('detalle', 'guardia', 'monto_total', 'fecha_inicio', 'fecha_fin');
        $nombre_reporte = 'reporte_alimentacion_guardia_' . preg_replace('/\s+/', '_', $guardia);
        $vista = 'seguridad.alimentacion_guardias';
        $export_excel = new ReporteAlimentacionGuardiasExport($reporte);

        return $tipo === 'consulta'
            ? response()->json($reporte)
            : $this->reporteService->imprimirReporte($tipo, 'A4', 'landscape', $reporte, $nombre_reporte, $vista, $export_excel);
    }

    private function mapearListado($bitacoras)
    {
        return $bitacoras->groupBy(fn($item) =>
            Carbon::parse($item->created_at)->format('Y-m-d')
        )->map(function ($items, $fecha) {
            $jornadas = $items->pluck('jornada')->unique()->toArray();
            $zona = $items->first()?->zona?->nombre ?? '-';

            return [
                'fecha' => $fecha,
                'zona' => $zona,
                'jornadas' => $jornadas,
                'monto' => count($jornadas) * 3, // $3 por jornada
            ];
        })->values();
    }
}
