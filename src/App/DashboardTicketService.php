<?php

namespace Src\App;

use App\Models\Empleado;
use App\Models\Subtarea;
use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class DashboardTicketService
{
    public function obtenerCantidadTicketsSolicitadosPorDepartamento()
    {
        // Obtencion de parametros
        $idEmpleado = request('empleado_id');
        $fechaInicio = request('fecha_inicio');
        $fechaFin = request('fecha_fin');

        // Conversion de fechas
        // $fechaInicio = Carbon::createFromFormat('d-m-Y', $fechaInicio)->format('Y-m-d');
        // $fechaFin = Carbon::createFromFormat('d-m-Y', $fechaFin)->addDay()->toDateString();
        $fechaInicio = Carbon::createFromFormat('Y-m-d', $fechaInicio)->startOfDay();
        $fechaFin = Carbon::createFromFormat('Y-m-d', $fechaFin)->endOfDay();

        return Ticket::where('solicitante_id', $idEmpleado)
            ->whereBetween('created_at', [$fechaInicio, $fechaFin])->orWhere('created_at', $fechaFin)
            ->get();
    }

    public function obtenerCantidadTicketsRecibidosPorDepartamento()
    {
        // Obtencion de parametros
        $idEmpleado = request('empleado_id');
        $fechaInicio = request('fecha_inicio');
        $fechaFin = request('fecha_fin');

        // Conversion de fechas
        // $fechaInicio = Carbon::createFromFormat('d-m-Y', $fechaInicio)->format('Y-m-d');
        // $fechaFin = Carbon::createFromFormat('d-m-Y', $fechaFin)->addDay()->toDateString();

        $fechaInicio = Carbon::createFromFormat('Y-m-d', $fechaInicio)->startOfDay();
        $fechaFin = Carbon::createFromFormat('Y-m-d', $fechaFin)->endOfDay();

        return  Ticket::where('responsable_id', $idEmpleado)
            ->whereBetween('created_at', [$fechaInicio, $fechaFin])->orWhere('created_at', $fechaFin)
            ->get();
    }

    private function obtenerIdsEmpleadosSubordinados()
    {
        $idEmpleado = request('empleado_id');
        return Empleado::where('jefe_id', $idEmpleado)->pluck('id');
    }

    public function obtenerTicketsFechaInicioFinEmpleadosSubordinados()
    {
        $idEmpleado = request('empleado_id');
        $fechaInicio = request('fecha_inicio');
        $fechaFin = request('fecha_fin');

        // $fechaInicio = Carbon::createFromFormat('d-m-Y', $fechaInicio)->format('Y-m-d');
        // $fechaFin = Carbon::createFromFormat('d-m-Y', $fechaFin)->addDay()->toDateString();

        $fechaInicio = Carbon::createFromFormat('Y-m-d', $fechaInicio)->startOfDay();
        $fechaFin = Carbon::createFromFormat('Y-m-d', $fechaFin)->endOfDay();

        $idsEmpleados = [...$this->obtenerIdsEmpleadosSubordinados(), $idEmpleado];

        return Ticket::whereIn('responsable_id', $idsEmpleados)->whereBetween('created_at', [$fechaInicio, $fechaFin])->orWhere('created_at', $fechaFin)->get();
    }

    public function obtenerCantidadTicketsPorDepartamentoEstado($estado)
    {
        $fechaInicio = request('fecha_inicio');
        $fechaFin = request('fecha_fin');

        // Conversion de fechas
        // $fechaInicio = Carbon::createFromFormat('d-m-Y', $fechaInicio)->format('Y-m-d');
        // $fechaFin = Carbon::createFromFormat('d-m-Y', $fechaFin)->addDay()->toDateString();

        $fechaInicio = Carbon::createFromFormat('Y-m-d', $fechaInicio)->startOfDay();
        $fechaFin = Carbon::createFromFormat('Y-m-d', $fechaFin)->endOfDay();

        $departamentoResponsableId = request('departamento_responsable_id');

        return Ticket::select('tickets.*', 'tickets.id', 'tickets.estado')
            ->join('empleados', 'tickets.responsable_id', '=', 'empleados.id')
            ->where('departamento_responsable_id', $departamentoResponsableId)
            ->where('tickets.estado', $estado)
            ->whereBetween('tickets.created_at', [$fechaInicio, $fechaFin])->orWhere('tickets.created_at', $fechaFin)
            ->get();
    }

    // Funciones para  obtener tickets por Categoria y Tipo
    public function obtenerTicketsPorCategoria()
{
    $idEmpleado = request('empleado_id');
    $fechaInicio = Carbon::createFromFormat('Y-m-d', request('fecha_inicio'))->startOfDay();
    $fechaFin = Carbon::createFromFormat('Y-m-d', request('fecha_fin'))->endOfDay();

    return Ticket::select('tickets.*', 'categorias_tipos_tickets.nombre as categoria')
        ->join('tipos_tickets', 'tickets.tipo_ticket_id', '=', 'tipos_tickets.id')
        ->join('categorias_tipos_tickets', 'tipos_tickets.categoria_tipo_ticket_id', '=', 'categorias_tipos_tickets.id')
        ->where('tickets.responsable_id', $idEmpleado)
        ->whereBetween('tickets.created_at', [$fechaInicio, $fechaFin])
        ->get();
}

    public function obtenerTicketsPorTipo()
    {
        $idEmpleado = request('empleado_id');
        $fechaInicio = Carbon::createFromFormat('Y-m-d', request('fecha_inicio'))->startOfDay();
        $fechaFin = Carbon::createFromFormat('Y-m-d', request('fecha_fin'))->endOfDay();

        return Ticket::select('tickets.*', 'tipos_tickets.nombre as tipo_ticket')
            ->join('tipos_tickets', 'tickets.tipo_ticket_id', '=', 'tipos_tickets.id')
            ->where('tickets.responsable_id', $idEmpleado)
            ->whereBetween('tickets.created_at', [$fechaInicio, $fechaFin])
            ->get();
    }

public function obtenerTicketsPorCategoriaDepartamento()
{
    $departamentoResponsableId = request('departamento_responsable_id');
    $fechaInicio = Carbon::createFromFormat('Y-m-d', request('fecha_inicio'))->startOfDay();
    $fechaFin = Carbon::createFromFormat('Y-m-d', request('fecha_fin'))->endOfDay();

    return Ticket::select(
            'categorias_tipos_tickets.nombre as categoria',
            DB::raw('COUNT(tickets.id) as total_tickets')
        )
        ->join('tipos_tickets', 'tickets.tipo_ticket_id', '=', 'tipos_tickets.id')
        ->join('categorias_tipos_tickets', 'tipos_tickets.categoria_tipo_ticket_id', '=', 'categorias_tipos_tickets.id')
        ->join('empleados', 'tickets.responsable_id', '=', 'empleados.id')
        ->where('empleados.departamento_id', $departamentoResponsableId)
        ->whereBetween('tickets.created_at', [$fechaInicio, $fechaFin])
        ->groupBy('categorias_tipos_tickets.id', 'categorias_tipos_tickets.nombre')
        ->get();
}

    public function obtenerTicketsPorTipoDepartamento()
    {
        $departamentoResponsableId = request('departamento_responsable_id');
        $fechaInicio = Carbon::createFromFormat('Y-m-d', request('fecha_inicio'))->startOfDay();
        $fechaFin = Carbon::createFromFormat('Y-m-d', request('fecha_fin'))->endOfDay();

        return Ticket::select('tickets.*', 'tipos_tickets.nombre as tipo_ticket', DB::raw('COUNT(tickets.id) as total_tickets'))
            ->join('tipos_tickets', 'tickets.tipo_ticket_id', '=', 'tipos_tickets.id')
            ->join('empleados', 'tickets.responsable_id', '=', 'empleados.id')
            ->where('empleados.departamento_id', $departamentoResponsableId)
            ->whereBetween('tickets.created_at', [$fechaInicio, $fechaFin])
            ->groupBy('tipos_tickets.id', 'tipos_tickets.nombre')
            ->get();
    }
}
