<?php

namespace App\Http\Controllers\Seguridad;

use App\Exports\Seguridad\AlimentacionGuardias\ReporteAlimentacionGuardiasExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Seguridad\ReporteAlimentacionRequest;
use App\Models\Seguridad\Bitacora;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Src\App\FondosRotativos\ReportePdfExcelService;
use Src\App\Seguridad\ReporteAlimentacionService;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ReporteAlimentacionController extends Controller
{
    private ReportePdfExcelService $reporteService;

    public function index(ReporteAlimentacionRequest $request): JsonResponse|BinaryFileResponse|Response
    {
        $this->reporteService = new ReportePdfExcelService();

        $tipo = $request->accion ?? 'consulta';
        $fecha_inicio = Carbon::parse($request->fecha_inicio)->format('Y-m-d');
        $fecha_fin = Carbon::parse($request->fecha_fin)->format('Y-m-d');

        // Si no se seleccionó un guardia, usar lógica general
        if (!$request->filled('empleado')) {
            return $this->generarReporteGeneral($request, $tipo, $fecha_inicio, $fecha_fin);
        }

        // Si se seleccionó un guardia, usar lógica individual
        return $this->generarReporteIndividual($request, $tipo, $fecha_inicio, $fecha_fin);
    }

    private function generarReporteGeneral($request, $tipo, $fecha_inicio, $fecha_fin)
    {
        $reporteService = new ReporteAlimentacionService();
        $reporte = $reporteService->generar($request->all());

        if (empty($reporte['detalle']) && $tipo === 'consulta') {
            return response()->json(['message' => 'No se encontraron registros para los filtros ingresados.'], 404);
        }

        $nombre_reporte = 'alimentacion_guardias';
        $titulo_hoja = substr($nombre_reporte, 0, 31);

        $vista = $tipo === 'excel'
            ? 'seguridad.excel.alimentacion_guardias'
            : 'seguridad.alimentacion_guardias';

        $export_excel = new ReporteAlimentacionGuardiasExport($reporte, $vista, $titulo_hoja);

        return $tipo === 'consulta'
            ? response()->json($reporte)
            : $this->reporteService->imprimirReporte(
                $tipo,
                'A4',
                'landscape',
                $reporte,
                $nombre_reporte,
                $vista,
                $export_excel
            );
    }

    private function generarReporteIndividual($request, $tipo, $fecha_inicio, $fecha_fin)
    {
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

        // Nombre base con nombre del guardia (limpiando caracteres inválidos)
        $nombre_reporte = 'alimentacion_guardia'; // Nombre corto y seguro
        $titulo_hoja = 'Guardia ' . substr($guardia, 0, 20); // Ejemplo: "Guardia Juan Perez"

        $vista = $tipo === 'excel'
            ? 'seguridad.excel.alimentacion_guardia_individual'
            : 'seguridad.alimentacion_guardia_individual';

        $export_excel = new ReporteAlimentacionGuardiasExport(
            $reporte,
            $vista,
            $titulo_hoja
        );
        return $tipo === 'consulta'
            ? response()->json($reporte)
            : $this->reporteService->imprimirReporte(
                $tipo,
                'A4',
                'landscape',
                $reporte,
                $nombre_reporte,
                $vista,
                $export_excel
            );
    }

    private function mapearListado($bitacoras)
    {
        return $bitacoras->groupBy(
            fn($item) =>
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

    /**
     * Limpia caracteres no válidos para archivos/hojas Excel y limita longitud
     */
    private function limpiarNombreArchivo(string $nombre, int $limite = 100): string
    {
        $nombre = preg_replace('/[\\\\\\/*?:\[\]]/', '', $nombre);
        return substr($nombre, 0, $limite);
    }
}
