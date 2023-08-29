<?php

namespace App\Http\Controllers;

use App\Http\Resources\SubtareaResource;
use App\Models\Empleado;
use App\Models\Subtarea;
use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Src\App\DashboardTareaService;

class DashboardTareaController extends Controller
{
    private DashboardTareaService $dashboardTareaService;

    public function __construct()
    {
        $this->dashboardTareaService = new DashboardTareaService();
    }

    public function index()
    {
        $idCoordinador = request('empleado_id');

        // Busqueda del coordinador
        $coordinador = Empleado::find($idCoordinador);

        $cantidadTareasActivas = $this->dashboardTareaService->obtenerCantidadTareasActivas($coordinador);
        $cantidadTareasFinalizadas = $this->dashboardTareaService->obtenerCantidadTareasFinalizadas($coordinador);

        $subtareasCoordinador = $this->dashboardTareaService->obtenerSubtareasFechaInicioFin($coordinador);
        $idsGruposCoordinador = $this->dashboardTareaService->obtenerIdsGruposCoordinador($idCoordinador);
        // Log::channel('testing')->info('Log', ['idsGruposCoordinador', $idsGruposCoordinador]);
        $subtareasGrupo = SubtareaResource::collection($this->dashboardTareaService->obtenerSubtareasFechaInicioFinGrupo($idsGruposCoordinador));
        $subtareasFechaInicioFin = $subtareasCoordinador->pluck('estado');
        $subtareasCoordinador = SubtareaResource::collection($subtareasCoordinador);

        $cantidadSubtareasAgendadas = $this->dashboardTareaService->contarCantidadSubtareasPorEstado($subtareasFechaInicioFin, Subtarea::AGENDADO);
        $cantidadSubtareasEjecutadas = $this->dashboardTareaService->contarCantidadSubtareasPorEstado($subtareasFechaInicioFin, Subtarea::EJECUTANDO);
        $cantidadSubtareasPausadas = $this->dashboardTareaService->contarCantidadSubtareasPorEstado($subtareasFechaInicioFin, Subtarea::PAUSADO);
        $cantidadSubtareasSuspendidas = $this->dashboardTareaService->contarCantidadSubtareasPorEstado($subtareasFechaInicioFin, Subtarea::SUSPENDIDO);
        $cantidadSubtareasCanceladas = $this->dashboardTareaService->contarCantidadSubtareasPorEstado($subtareasFechaInicioFin, Subtarea::CANCELADO);
        $cantidadSubtareasRealizadas = $this->dashboardTareaService->contarCantidadSubtareasPorEstado($subtareasFechaInicioFin, Subtarea::REALIZADO);
        $cantidadSubtareasFinalizadas = $this->dashboardTareaService->contarCantidadSubtareasPorEstado($subtareasFechaInicioFin, Subtarea::FINALIZADO);

        $cantidadesPorEstadosSubtareas = $this->dashboardTareaService->generarListadoCantidadesPorEstadosSubtareas(
            $cantidadSubtareasAgendadas,
            $cantidadSubtareasEjecutadas,
            $cantidadSubtareasPausadas,
            $cantidadSubtareasSuspendidas,
            $cantidadSubtareasCanceladas,
            $cantidadSubtareasRealizadas,
            $cantidadSubtareasFinalizadas
        );

        // Listados
        $cantidadesSubtareasPorGrupo = $this->obtenerCantidadSubtareasPorGrupos($idCoordinador);

        // Respuesta
        $results = compact(
            'cantidadTareasActivas',
            'cantidadTareasFinalizadas',
            'cantidadSubtareasAgendadas',
            'cantidadSubtareasEjecutadas',
            'cantidadSubtareasPausadas',
            'cantidadSubtareasSuspendidas',
            'cantidadSubtareasCanceladas',
            'cantidadSubtareasRealizadas',
            'cantidadSubtareasFinalizadas',
            'subtareasCoordinador',
            'subtareasGrupo',
            'cantidadesPorEstadosSubtareas',
            'cantidadesSubtareasPorGrupo',
        );

        return response()->json(compact('results'));
    }

    private function obtenerCantidadSubtareasPorGrupos($idCoordinador)
    {
        $fechaInicio = request('fecha_inicio');
        $fechaFin = request('fecha_fin');

        // Conversion de fechas
        $fechaInicio = Carbon::createFromFormat('d-m-Y', $fechaInicio)->format('Y-m-d');
        $fechaFin = Carbon::createFromFormat('d-m-Y', $fechaFin)->addDay()->toDateString();

        return DB::table('subtareas')->select('grupos.nombre', DB::raw('COUNT(subtareas.grupo_id) as total_subtareas'), 'subtareas.estado')
            ->join('tareas', 'subtareas.tarea_id', '=', 'tareas.id')
            ->join('grupos', 'subtareas.grupo_id', '=', 'grupos.id')
            ->where('tareas.coordinador_id', $idCoordinador)
            ->whereBetween('subtareas.created_at', [$fechaInicio, $fechaFin])
            ->groupBy('subtareas.grupo_id')
            ->groupBy('subtareas.estado')
            ->get();
    }
}

/**
 select grupos.nombre, subtareas.estado, count(subtareas.grupo_id) as cantidad_subtareas from subtareas
inner join tareas on subtareas.tarea_id = tareas.id
inner join grupos on subtareas.grupo_id = grupos.id
where tareas.coordinador_id = 7
group by subtareas.grupo_id;

 */
