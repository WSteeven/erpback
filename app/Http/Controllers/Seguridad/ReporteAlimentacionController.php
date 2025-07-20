<?php

namespace App\Http\Controllers\Seguridad;


use App\Exports\Seguridad\AlimentacionGuardias\ReporteAlimentacionGuardiasExport;
use App\Http\Controllers\Controller;

use App\Http\Requests\Seguridad\ReporteAlimentacionRequest;
use App\Models\Seguridad\Bitacora;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Src\App\FondosRotativos\ReportePdfExcelService;

class ReporteAlimentacionController extends Controller
{

    private ReportePdfExcelService $reporteService;

    public function index(ReporteAlimentacionRequest $request)
    {
        $this->reporteService = new ReportePdfExcelService();
        $tipo = $request->accion;
        $fecha_inicio = Carbon::createFromFormat('Y-m-d', $request->fecha_inicio);
        $fecha_fin = Carbon::createFromFormat('Y-m-d', $request->fecha_fin);
        $fecha_inicio = $fecha_inicio->format('Y-m-d');
        $fecha_fin = $fecha_fin->format('Y-m-d');

        $bitacoras = Bitacora::with(['zona', 'agenteTurno'])
            ->whereBetween('created_at', [$request->fecha_inicio, $request->fecha_fin])
            ->when($request->empleado, fn($q) => $q->where('agente_turno_id', $request->empleado))
            ->when($request->zona, fn($q) => $q->where('zona_id', $request->zona))
            ->when($request->jornada, fn($q) => $q->where('jornada', $request->jornada))
            ->get();

        Log::channel('testing')->info('Log', ['bitacoras', $bitacoras->first()]);
        $guardia = optional($bitacoras->first()?->agenteTurno)->nombres . ' ' . optional($bitacoras->first()?->agenteTurno)->apellidos ?? '-' ?? 'N/A';

        $detalle = $this->mapearListado($bitacoras);
        $monto_total = $detalle->sum('monto');

        $reporte = compact('detalle', 'guardia', 'monto_total', 'fecha_inicio', 'fecha_fin');

        $nombre_reporte = 'reporte_alimentacion_guardia_' . $guardia;

        $vista = 'seguridad.alimentacion_guardias';

        $export_excel = new ReporteAlimentacionGuardiasExport($reporte);

        return $this->reporteService->imprimirReporte($tipo, 'A4', 'landscape', $reporte, $nombre_reporte, $vista, $export_excel);
    }

    /*

    Esta funcion no agrupa por fecha, tiene mas detalles como la hora en la que se realizo la bitacora

    private function mapearListado($bitacoras)
    {
        return $bitacoras->groupBy('created_at')->map(function ($items, $fecha) {
            $jornadas = $items->pluck('jornada')->unique()->toArray();
            $zona = $items->first()?->zona?->nombre ?? '-';

            return [
                'fecha' => $fecha,
                'zona' => $zona,
                'jornadas' => $jornadas,
                'monto' => count($jornadas) * 3, // $3 por jornada
            ];
        })->values();
    } */

    private function mapearListado($bitacoras)
    {
        return $bitacoras->groupBy(function ($item) {
            return Carbon::parse($item->created_at)->format('Y-m-d');
        })->map(function ($items, $fecha) {
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
