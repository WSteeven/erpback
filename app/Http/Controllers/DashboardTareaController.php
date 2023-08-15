<?php

namespace App\Http\Controllers;

use App\Http\Resources\SubtareaResource;
use App\Models\Empleado;
use App\Models\Subtarea;
use App\Models\Ticket;
use Illuminate\Http\Request;
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

        $subtareas = $this->dashboardTareaService->obtenerSubtareasFechaInicioFin($coordinador);
        $subtareasFechaInicioFin = $subtareas->pluck('estado');
        $subtareas = SubtareaResource::collection($subtareas);

        $cantidadSubtareasAgendadas = $this->dashboardTareaService->filtrarCantidadSubtareasPorEstado($subtareasFechaInicioFin, Subtarea::AGENDADO);
        $cantidadSubtareasEjecutadas = $this->dashboardTareaService->filtrarCantidadSubtareasPorEstado($subtareasFechaInicioFin, Subtarea::EJECUTANDO);
        $cantidadSubtareasPausadas = $this->dashboardTareaService->filtrarCantidadSubtareasPorEstado($subtareasFechaInicioFin, Subtarea::PAUSADO);
        $cantidadSubtareasSuspendidas = $this->dashboardTareaService->filtrarCantidadSubtareasPorEstado($subtareasFechaInicioFin, Subtarea::SUSPENDIDO);
        $cantidadSubtareasCanceladas = $this->dashboardTareaService->filtrarCantidadSubtareasPorEstado($subtareasFechaInicioFin, Subtarea::CANCELADO);
        $cantidadSubtareasRealizadas = $this->dashboardTareaService->filtrarCantidadSubtareasPorEstado($subtareasFechaInicioFin, Subtarea::REALIZADO);
        $cantidadSubtareasFinalizadas = $this->dashboardTareaService->filtrarCantidadSubtareasPorEstado($subtareasFechaInicioFin, Subtarea::FINALIZADO);

        $cantidadesPorEstadosSubtareas = $this->dashboardTareaService->generarListadoCantidadesPorEstadosSubtareas(
            $cantidadSubtareasAgendadas,
            $cantidadSubtareasEjecutadas,
            $cantidadSubtareasPausadas,
            $cantidadSubtareasSuspendidas,
            $cantidadSubtareasCanceladas,
            $cantidadSubtareasRealizadas,
            $cantidadSubtareasFinalizadas
        );

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
            'subtareas',
            'cantidadesPorEstadosSubtareas',
        );

        return response()->json(compact('results'));
    }
}
