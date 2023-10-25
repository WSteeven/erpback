<?php

namespace App\Http\Controllers;

use App\Http\Resources\SubtareaResource;
use Src\App\DashboardTareaService;
use App\Models\Empleado;
use App\Models\Subtarea;

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
        $campos = request('campos') ? explode(',', request('campos')) : '*';

        // Busqueda del coordinador
        $coordinador = Empleado::find($idCoordinador);

        $cantidadTareasActivas = $this->dashboardTareaService->obtenerCantidadTareasActivas($coordinador);
        $cantidadTareasFinalizadas = $this->dashboardTareaService->obtenerCantidadTareasFinalizadas($coordinador);

        $subtareasCoordinador = $this->dashboardTareaService->obtenerSubtareasFechaInicioFin($coordinador, $campos);
        $subtareasFechaInicioFin = $subtareasCoordinador->pluck('estado');

        $cantidadSubtareasAgendadas = $this->dashboardTareaService->contarCantidadSubtareasPorEstado($subtareasFechaInicioFin, Subtarea::AGENDADO);
        $cantidadSubtareasEjecutadas = $this->dashboardTareaService->contarCantidadSubtareasPorEstado($subtareasFechaInicioFin, Subtarea::EJECUTANDO);
        $cantidadSubtareasPausadas = $this->dashboardTareaService->contarCantidadSubtareasPorEstado($subtareasFechaInicioFin, Subtarea::PAUSADO);
        $cantidadSubtareasSuspendidas = $this->dashboardTareaService->contarCantidadSubtareasPorEstado($subtareasFechaInicioFin, Subtarea::SUSPENDIDO);
        $cantidadSubtareasCanceladas = $this->dashboardTareaService->contarCantidadSubtareasPorEstado($subtareasFechaInicioFin, Subtarea::CANCELADO);
        $cantidadSubtareasRealizadas = $this->dashboardTareaService->contarCantidadSubtareasPorEstado($subtareasFechaInicioFin, Subtarea::REALIZADO);
        $cantidadSubtareasFinalizadas = $this->dashboardTareaService->contarCantidadSubtareasPorEstado($subtareasFechaInicioFin, Subtarea::FINALIZADO);

        /********
         Listados
         *********/
        $cantidadesPorEstadosSubtareas = $this->dashboardTareaService->generarListadoCantidadesPorEstadosSubtareas(
            $cantidadSubtareasAgendadas,
            $cantidadSubtareasEjecutadas,
            $cantidadSubtareasPausadas,
            $cantidadSubtareasSuspendidas,
            $cantidadSubtareasCanceladas,
            $cantidadSubtareasRealizadas,
            $cantidadSubtareasFinalizadas
        );

        $subtareasCoordinador = SubtareaResource::collection($subtareasCoordinador);
        $idsGruposCoordinador = $this->dashboardTareaService->obtenerIdsGruposCoordinador($idCoordinador);
        $subtareasGrupo = SubtareaResource::collection($this->dashboardTareaService->obtenerSubtareasFechaInicioFinGrupo($idsGruposCoordinador, $idCoordinador));
        $idsEmpleadosCoordinador = $this->dashboardTareaService->obtenerIdsEmpleadosCoordinador($idCoordinador);
        $subtareasEmpleado = SubtareaResource::collection($this->dashboardTareaService->obtenerSubtareasFechaInicioFinEmpleado($idsEmpleadosCoordinador, $idCoordinador));

        /**********
        Respuesta
        ***********/
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
            'subtareasEmpleado',
            'subtareasGrupo',
            'cantidadesPorEstadosSubtareas',
        );

        return response()->json(compact('results'));
    }
}
