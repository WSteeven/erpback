<?php

namespace Src\App;

use App\Models\Empleado;
use App\Models\Subtarea;
use App\Models\Ticket;
use Carbon\Carbon;
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

    public function filtrarCantidadSubtareasPorEstado($subtareasFechaInicioFin, $estadoSubtarea)
    {
        return $subtareasFechaInicioFin->filter(fn ($subtarea) => $subtarea === $estadoSubtarea)->count();
    }

    public function obtenerSubtareasFechaInicioFin(Empleado $coordinador)
    {
        $fechaInicio = request('fecha_inicio');
        $fechaFin = request('fecha_fin');

        $fechaInicio = Carbon::createFromFormat('d-m-Y', $fechaInicio)->format('Y-m-d');
        $fechaFin = Carbon::createFromFormat('d-m-Y', $fechaFin)->addDay()->toDateString();

        return Subtarea::whereIn('tarea_id', function ($query) use ($fechaInicio, $fechaFin, $coordinador) {
            $query->select('id')
                ->from('tareas')
                ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                ->where('coordinador_id', $coordinador->id);
        })->get();
        // ->pluck('estado');
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
