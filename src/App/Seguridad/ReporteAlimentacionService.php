<?php

namespace Src\App\Seguridad;

use App\Models\Seguridad\Bitacora;
use Illuminate\Support\Collection;

class ReporteAlimentacionService
{

    /**
     * Summary of __construct
     */
    public function __construct() {}

    /**
     * Summary of generar
     * @param array $filtros
     * @return array{detalle: Collection<int, array>, guardia: mixed, monto_total: mixed|array{detalle: Collection<int, array>, monto_total: mixed}}
     */
    public function generar(array $filtros): array
    {
        $query = Bitacora::query()
            ->with(['empleado', 'zona']) // usa accessor getEmpleadoAttribute()
            ->whereBetween('fecha_hora_inicio_turno', [$filtros['fecha_inicio'], $filtros['fecha_fin']]);

        if (!empty($filtros['empleado'])) {
            $query->where('agente_turno_id', $filtros['empleado']);
        }

        if (!empty($filtros['zona'])) {
            $query->where('zona_id', $filtros['zona']);
        }

        if (!empty($filtros['jornada'])) {
            $query->where('jornada', $filtros['jornada']);
        }

        $bitacoras = $query->get();

        // Si se seleccionó un guardia, agrupamos por fecha
        if (!empty($filtros['empleado'])) {
            $detalle = $bitacoras->groupBy('fecha_hora_inicio_turno->format("Y-m-d")')->map(function ($items) {
                $jornadas = $items->pluck('jornada')->unique();
                $zona = $items->first()->zona->nombre ?? '-';

                return [
                    'fecha' => $items->first()->fecha_hora_inicio_turno->format('Y-m-d'),
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

        // Si no se seleccionó un guardia → agrupar por guardia primero
        $porGuardia = $bitacoras->groupBy('empleado.id');

        $detalle = $porGuardia->map(function ($registros, $guardiaId) {
            $empleado = $registros->first()->empleado;
            $nombre = $empleado ? "{$empleado->nombres} {$empleado->apellidos}" : 'N/A';

            $detallePorFecha = $registros->groupBy(fn($item) => $item->fecha_hora_inicio_turno->format('Y-m-d'))->map(function ($items) {
                $jornadas = $items->pluck('jornada')->unique();
                $zona = $items->first()->zona->nombre ?? '-';

                return [
                    'fecha' => $items->first()->fecha_hora_inicio_turno->format('Y-m-d'),
                    'zona' => $zona,
                    'jornadas' => $jornadas->toArray(),
                    'monto' => count($jornadas) * 3,
                ];
            })->values();

            return [
                'guardia' => $nombre,
                'detalle' => $detallePorFecha,
                'monto_total' => $detallePorFecha->sum('monto'),
            ];
        })->values();

        return [
            'detalle' => $detalle,
            'total' => [
                'guardia' => 'TODOS',
                'monto_total' => $detalle->sum('monto_total'),
            ],
            'fecha_inicio' => $filtros['fecha_inicio'],
            'fecha_fin' => $filtros['fecha_fin'],
        ];
    }
}
