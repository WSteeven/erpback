<?php

namespace App\Http\Controllers\Seguridad;

use App\Http\Controllers\Controller;

use App\Http\Requests\Seguridad\ReporteAlimentacionRequest;
use App\Models\Seguridad\Bitacora;

use Illuminate\Support\Facades\DB;

class ReporteAlimentacionController extends Controller
{
    public function index(ReporteAlimentacionRequest $request)
    {

        $bitacoras = Bitacora::with(['zona', 'empleado'])
            ->whereBetween('fecha', [$request->fecha_inicio, $request->fecha_fin])
            ->when($request->empleado, fn ($q) => $q->where('empleado_id', $request->empleado))
            ->when($request->zona, fn ($q) => $q->where('zona_id', $request->zona))
            ->when($request->jornada, fn ($q) => $q->where('jornada', $request->jornada))
            ->get();

        $guardia = optional($bitacoras->first()?->empleado)->nombre_completo ?? 'N/A';

        $detalle = $this->mapearListado($bitacoras);
        $monto_total = $detalle->sum('monto');

        return response()->json([
            'results' => $detalle,
            'guardia' => $guardia,
            'monto_total' => $monto_total,
        ]);
    }

    private function mapearListado($bitacoras)
    {
        return $bitacoras->groupBy('fecha')->map(function ($items, $fecha) {
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
