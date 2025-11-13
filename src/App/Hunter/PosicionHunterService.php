<?php

namespace Src\App\Hunter;

use App\Models\Conecel\GestionTareas\Tarea;
use Illuminate\Database\Eloquent\Collection;
use Log;
use Src\Shared\Utils;

class PosicionHunterService
{
    public function __construct()
    {
    }


    public static function mapearGruposVehiculosTareas(Collection|\Illuminate\Support\Collection $grupos, Collection $posiciones): array
    {
        $resultados = [];
        $posicionesIndexadas = $posiciones->keyBy('placa');

        // Obtenemos todos los nombres de grupos para luego excluir sus tareas
        $nombresGrupos = $grupos->pluck('nombre_alternativo')->filter()->toArray();

        // Pre-cargar tareas una sola vez
        $todasLasTareas = Tarea::whereIn('astatus', request()->astatus)->whereRaw("JSON_EXTRACT(raw_data, '$._v.D') = ?", [request()->raw_data])->get();

        // Indexamos tareas por source (nombre del grupo)
        $tareasPorGrupo = [];
        foreach ($todasLasTareas as $tarea) {
            if ($tarea->source && in_array($tarea->source, $nombresGrupos)) {
                $tareasPorGrupo[$tarea->source][] = $tarea;
            }
        }

        foreach ($grupos as $index => $grupo) {
            $vehiculo = $grupo->vehiculo;
            $posicionGPS = $posicionesIndexadas->get($vehiculo?->placa);

            $nombreGrupo = $grupo->nombre_alternativo ?? $grupo->nombre;

            $row = [
                'id' => $grupo->id,
                'nombre' => $nombreGrupo,
                'activo' => $grupo->activo,
                'color' => Utils::coloresAleatorios()[$index % count(Utils::coloresAleatorios())],
                'vehiculo' => [
                    'placa' => $vehiculo?->placa ?? 'SIN PLACA',
                    'coordenadas' => $posicionGPS ? [
                        'lat' => (float)$posicionGPS->lat,
                        'lng' => (float)$posicionGPS->lng,
                    ] : null
                ],
                'tareas' => []
            ];

            $tareasDelGrupo = $tareasPorGrupo[$nombreGrupo] ?? [];

            foreach ($tareasDelGrupo as $tarea) {
                if (!$tarea->lat || !$tarea->lng) continue;

                $row['tareas'][] = [
                    'id' => $tarea->aid. (int)preg_replace('/\D/', '', $tarea->appt_number), // Elimina todo lo que NO sea dígito
                    'titulo' => trim("$tarea->aworktype - $tarea->cname - $tarea->direccion - $tarea->astatus"),
                    'orden_trabajo' => $tarea->appt_number ?? null,
                    'color' => Utils::obtenerEstiloPorEstado($tarea->astatus)['color'],
                    'coordenadas' => [
                        'lat' => (float)$tarea->lat,
                        'lng' => (float)$tarea->lng,
                    ]
                ];
            }

            $resultados[] = $row;
        }

        return $resultados;
    }

    /**
     * NUEVO: Devuelve todas las tareas que NO pertenecen a ningún grupo activo
     */
    public static function obtenerTareasSinGrupo(Collection|\Illuminate\Support\Collection $grupos): array
    {
        $nombresGrupos = $grupos->pluck('nombre_alternativo')->filter()->toArray();

        $tareas = Tarea::whereIn('astatus', request()->astatus)->whereRaw("JSON_EXTRACT(raw_data, '$._v.D') = ?", [request()->raw_data])
            ->where(function ($query) use ($nombresGrupos) {
                $query->whereNull('source')
                    ->orWhereNotIn('source', $nombresGrupos);
            })
            ->orderBy('id', 'desc')
            ->get();

        $resultados = [];
        foreach ($tareas as $tarea) {
            if (!$tarea->lat || !$tarea->lng) continue;

            $resultados[] = [
                'id' =>$tarea->aid. (int)preg_replace('/\D/', '', $tarea->appt_number), // Elimina todo lo que NO sea dígito
                'titulo' => trim("$tarea->aworktype - $tarea->cname - $tarea->direccion - $tarea->astatus"),
                'coordenadas' => [
                    'lat' => (float)$tarea->lat,
                    'lng' => (float)$tarea->lng,
                ]
            ];
        }

//        Log::channel('testing')->info('tareas_sin_grupo -> count', ['total' => count($resultados)]);

        return $resultados;
    }


}
