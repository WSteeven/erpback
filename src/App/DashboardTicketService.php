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
        $fechaInicio = Carbon::createFromFormat('d-m-Y', $fechaInicio)->format('Y-m-d');
        $fechaFin = Carbon::createFromFormat('d-m-Y', $fechaFin)->addDay()->toDateString();

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
        $fechaInicio = Carbon::createFromFormat('d-m-Y', $fechaInicio)->format('Y-m-d');
        $fechaFin = Carbon::createFromFormat('d-m-Y', $fechaFin)->addDay()->toDateString();

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

        $fechaInicio = Carbon::createFromFormat('d-m-Y', $fechaInicio)->format('Y-m-d');
        $fechaFin = Carbon::createFromFormat('d-m-Y', $fechaFin)->addDay()->toDateString();
        $idsEmpleados = [...$this->obtenerIdsEmpleadosSubordinados(), $idEmpleado];

        return Ticket::whereIn('responsable_id', $idsEmpleados)->whereBetween('created_at', [$fechaInicio, $fechaFin])->orWhere('created_at', $fechaFin)->get();
    }

    public function obtenerCantidadTicketsPorDepartamentoEstado($estado)
    {
        $fechaInicio = request('fecha_inicio');
        $fechaFin = request('fecha_fin');

        // Conversion de fechas
        $fechaInicio = Carbon::createFromFormat('d-m-Y', $fechaInicio)->format('Y-m-d');
        $fechaFin = Carbon::createFromFormat('d-m-Y', $fechaFin)->addDay()->toDateString();

        $departamentoResponsableId = request('departamento_responsable_id');

        return Ticket::select('*', 'tickets.id', 'tickets.estado')
            ->join('empleados', 'tickets.responsable_id', '=', 'empleados.id')
            ->where('departamento_responsable_id', $departamentoResponsableId)
            ->where('tickets.estado', $estado)
            ->whereBetween('tickets.created_at', [$fechaInicio, $fechaFin])->orWhere('tickets.created_at', $fechaFin)
            ->get();

        /* return DB::table('tickets')->select(DB::raw("CONCAT(empleados.nombres, ' ', empleados.apellidos) AS responsable"), 'tickets.*')
            ->join('empleados', 'tickets.responsable_id', '=', 'empleados.id')
            ->where('departamento_responsable_id', $departamentoResponsableId)
            ->where('tickets.estado', $estado)
            ->whereBetween('tickets.created_at', [$fechaInicio, $fechaFin])->orWhere('tickets.created_at', $fechaFin)
            ->get(); */

        /* return DB::table('tickets')->select(DB::raw("CONCAT(empleados.nombres, ' ', empleados.apellidos) AS responsable"), DB::raw('COUNT(tickets.codigo) as total_tickets'), 'tickets.estado')
            ->join('empleados', 'tickets.responsable_id', '=', 'empleados.id')
            ->where('departamento_responsable_id', $departamentoResponsableId)
            ->where('tickets.estado', $estado)
            ->whereBetween('tickets.created_at', [$fechaInicio, $fechaFin])->orWhere('tickets.created_at', $fechaFin)
            ->groupBy('responsable')
            ->get(); */
    }
}
