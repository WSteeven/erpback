<?php

namespace App\Application\Seguridad;

use App\Models\Seguridad\Bitacora;
use Illuminate\Support\Collection;

class ReporteAlimentacionService
{
    public function generar(array $filtros): array
    {
        $query = Bitacora::query()
            ->with(['empleado', 'zona'])
            ->whereBetween('fecha', [$filtros['fecha_inicio'], $filtros['fecha_fin']]);

        if (!empty($filtros['empleado'])) {
            $query->where('empleado_id', $filtros['empleado']);
        }

        if (!empty($filtros['zona'])) {
            $query->where('zona_id', $filtros['zona']);
        }

        if (!empty($filtros['jornada'])) {
            $query->where('jornada', $filtros['jornada']);
        }

        $bitacoras = $query->get();

        $detalle = $bitacoras->groupBy('fecha')->map(function ($items) {
            $jornadas = $items->pluck('jornada')->unique();
            $zona = $items->first()->zona->nombre ?? '-';

            return [
                'fecha' => $items->first()->fecha,
                'zona' => $zona,
                'jornadas' => $jornadas->toArray(),
                'monto' => count($jornadas) * 3,
            ];
        })->values();

        return [
            'detalle' => $detalle,
            'total' => [
                'guardia' => optional($bitacoras->first()->empleado)->nombre_completo ?? 'N/A',
                'monto_total' => $detalle->sum('monto'),
            ],
            'fecha_inicio' => $filtros['fecha_inicio'],
            'fecha_fin' => $filtros['fecha_fin'],
        ];
    }
}
