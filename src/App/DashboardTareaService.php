<?php

namespace Src\App;

use App\Models\Empleado;
use App\Models\Subtarea;
use App\Models\Tarea;
use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class DashboardTareaService
{
    // public function __construct() { }

    public function obtenerCantidadTareasActivas(Empleado $coordinador)
    {
        return $coordinador->tareasCoordinador()->where('finalizado', 0)->fechaInicioFin()->count();
    }

    public function obtenerCantidadTareasFinalizadas(Empleado $coordinador)
    {
        return $coordinador->tareasCoordinador()->where('finalizado', 1)->fechaInicioFin()->count();
    }

    public function obtenerSubtareasFinalizadas(Empleado $coordinador)
    {
        $tareas_id = $coordinador->tareasCoordinador()->where('finalizado', 1)->fechaInicioFin()->pluck('id');

        $subtareas = Subtarea::select('id', 'codigo_subtarea', 'fecha_hora_ejecucion', 'fecha_hora_realizado', 'tarea_id', 'empleado_id')->whereIn('tarea_id', $tareas_id)->get();

        $subtareas = $subtareas->map(fn ($item) => [
            'id' => $item->id,
            'tarea_id' => $item->tarea_id,
            'empleado_id' => $item->empleado_id,
            'codigo_subtarea' => $item->codigo_subtarea,
            'tiempo' => Carbon::parse($item->fecha_hora_realizado)->diffInHours(Carbon::parse($item->fecha_hora_ejecucion)),
        ]);

        return $subtareas;
    }

    public function obtenerSubtareasRealizadas(Empleado $coordinador)
    {
        $tareas_id = $coordinador->tareasCoordinador()->where('finalizado', 1)->fechaInicioFin()->pluck('id');

        $subtareas = Subtarea::select('id', 'codigo_subtarea', 'fecha_hora_ejecucion', 'fecha_hora_realizado', 'tarea_id', 'empleado_id')->whereIn('tarea_id', $tareas_id)->get();

        $subtareas = $subtareas->map(fn ($item) => [
            'id' => $item->id,
            'tarea_id' => $item->tarea_id,
            'empleado_id' => $item->empleado_id,
            'codigo_subtarea' => $item->codigo_subtarea,
            'tiempo' => Carbon::parse($item->fecha_hora_finalizacion)->diffInHours(Carbon::parse($item->fecha_hora_realizado)),
        ]);

        return $subtareas;
    }

    public function contarCantidadSubtareasPorEstado($subtareasFechaInicioFin, $estadoSubtarea)
    {
        return $subtareasFechaInicioFin->filter(fn ($subtarea) => $subtarea === $estadoSubtarea)->count();
    }

    public function obtenerSubtareasFechaInicioFin(Empleado $coordinador, $campos)
    {
        $fechaInicio = request('fecha_inicio');
        $fechaFin = request('fecha_fin');

        $fechaInicio = Carbon::createFromFormat('d-m-Y', $fechaInicio)->format('Y-m-d');
        $fechaFin = Carbon::createFromFormat('d-m-Y', $fechaFin)->addDay()->toDateString();

        //$camposConsultar = array_diff($campos, Subtarea::$noFiltrar);

        return Subtarea::whereIn('tarea_id', function ($query) use ($fechaInicio, $fechaFin, $coordinador) {
            $query->select('id')
                ->from('tareas')
                ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                ->where('coordinador_id', $coordinador->id);
        })->get();
    }

    public function obtenerSubtareasFechaInicioFinGrupo($idsGrupos, $idCoordinador)
    {
        $fechaInicio = request('fecha_inicio');
        $fechaFin = request('fecha_fin');

        $fechaInicio = Carbon::createFromFormat('d-m-Y', $fechaInicio)->format('Y-m-d');
        $fechaFin = Carbon::createFromFormat('d-m-Y', $fechaFin)->addDay()->toDateString();

        return Subtarea::whereIn('grupo_id', $idsGrupos)->whereIn('tarea_id', function ($query) use ($fechaInicio, $fechaFin, $idCoordinador) {
            $query->select('id')
                ->from('tareas')
                ->where('coordinador_id', $idCoordinador)
                ->whereBetween('created_at', [$fechaInicio, $fechaFin]);
        })->get();
    }

    public function obtenerSubtareasFechaInicioFinEmpleado($idsEmpleados, $idCoordinador)
    {
        $fechaInicio = request('fecha_inicio');
        $fechaFin = request('fecha_fin');

        $fechaInicio = Carbon::createFromFormat('d-m-Y', $fechaInicio)->format('Y-m-d');
        $fechaFin = Carbon::createFromFormat('d-m-Y', $fechaFin)->addDay()->toDateString();

        return Subtarea::whereIn('empleado_id', $idsEmpleados)->whereIn('tarea_id', function ($query) use ($fechaInicio, $fechaFin, $idCoordinador) {
            $query->select('id')
                ->from('tareas')
                ->where('coordinador_id', $idCoordinador)
                ->whereBetween('created_at', [$fechaInicio, $fechaFin]);
        })->get();
    }

    public function obtenerIdsGruposCoordinador(int $idCoordinador)
    {
        return DB::table('subtareas')
            ->join('tareas', 'tareas.id', '=', 'subtareas.tarea_id')
            ->where('tareas.coordinador_id', $idCoordinador)
            ->groupBy('subtareas.grupo_id')
            ->select('subtareas.grupo_id')
            ->pluck('grupo_id');
    }

    public function obtenerIdsEmpleadosCoordinador(int $idCoordinador)
    {
        return DB::table('subtareas')
            ->join('tareas', 'tareas.id', '=', 'subtareas.tarea_id')
            ->where('tareas.coordinador_id', $idCoordinador)
            ->groupBy('subtareas.empleado_id')
            ->select('subtareas.empleado_id')
            ->pluck('empleado_id');
    }

    public function generarListadoCantidadesPorEstadosSubtareas(
        $cantidadSubtareasAgendadas,
        $cantidadSubtareasEjecutadas,
        $cantidadSubtareasPausadas,
        $cantidadSubtareasSuspendidas,
        $cantidadSubtareasCanceladas,
        $cantidadSubtareasRealizadas,
        $cantidadSubtareasFinalizadas
    ) {
        return [
            [
                'estado' => Subtarea::AGENDADO,
                'total_subtareas' => $cantidadSubtareasAgendadas
            ],
            [
                'estado' => Subtarea::EJECUTANDO,
                'total_subtareas' => $cantidadSubtareasEjecutadas
            ],
            [
                'estado' => Subtarea::PAUSADO,
                'total_subtareas' => $cantidadSubtareasPausadas
            ],
            [
                'estado' => Subtarea::SUSPENDIDO,
                'total_subtareas' => $cantidadSubtareasSuspendidas
            ],
            [
                'estado' => Subtarea::CANCELADO,
                'total_subtareas' => $cantidadSubtareasCanceladas
            ],
            [
                'estado' => Subtarea::REALIZADO,
                'total_subtareas' => $cantidadSubtareasRealizadas
            ],
            [
                'estado' => Subtarea::FINALIZADO,
                'total_subtareas' => $cantidadSubtareasFinalizadas
            ],
        ];
    }
}
