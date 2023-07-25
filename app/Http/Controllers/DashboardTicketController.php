<?php

namespace App\Http\Controllers;

use App\Models\CalificacionTicket;
use App\Models\Empleado;
use App\Models\Ticket;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardTicketController extends Controller
{
    public function index()
    {
        // Obtencion de parametros
        $idEmpleado = request('empleado_id');
        $fechaInicio = request('fecha_inicio');
        $fechaFin = request('fecha_fin');

        // Conversion de fechas
        $fechaInicio = Carbon::createFromFormat('d-m-Y', $fechaInicio)->format('Y-m-d');
        $fechaFin = Carbon::createFromFormat('d-m-Y', $fechaFin)->addDay()->toDateString();

        // Busqueda de empleado
        $empleado = Empleado::find($idEmpleado);

        // Cosulta de datos
        $cantTicketsCreados = $empleado->ticketsSolicitados()->where(function ($query) use ($fechaInicio, $fechaFin) {
            $query->whereBetween('created_at', [$fechaInicio, $fechaFin])->orWhere('created_at', $fechaFin);
        })->count();

        $cantTicketsRecibidos = $empleado->tickets()->whereBetween('created_at', [$fechaInicio, $fechaFin])->orWhere('created_at', $fechaFin)->count();
        $cantTicketsAsignados = $empleado->tickets()->where('estado', Ticket::ASIGNADO)->whereBetween('created_at', [$fechaInicio, $fechaFin])->orWhere('created_at', $fechaFin)->count();
        $cantTicketsReasignados = $empleado->tickets()->where('estado', Ticket::REASIGNADO)->whereBetween('created_at', [$fechaInicio, $fechaFin])->orWhere('created_at', $fechaFin)->count();
        $cantTicketsEjecutados = $empleado->tickets()->where('estado', Ticket::EJECUTANDO)->whereBetween('created_at', [$fechaInicio, $fechaFin])->orWhere('created_at', $fechaFin)->count();
        $cantTicketsPausados = $empleado->tickets()->where('estado', Ticket::PAUSADO)->whereBetween('created_at', [$fechaInicio, $fechaFin])->orWhere('created_at', $fechaFin)->count();
        // $cantTicketsCalificados = $empleado->tickets()->where('estado', Ticket::CALIFICADO)->whereBetween('created_at', [$fechaInicio, $fechaFin])->orWhere('created_at', $fechaFin)->count();
        $cantTicketsFinalizadosSolucionados = $empleado->tickets()->where('estado', Ticket::FINALIZADO_SOLUCIONADO)->whereBetween('created_at', [$fechaInicio, $fechaFin])->orWhere('created_at', $fechaFin)->count();
        $cantTicketsFinalizadosSinSolucion = $empleado->tickets()->where('estado', Ticket::FINALIZADO_SIN_SOLUCION)->whereBetween('created_at', [$fechaInicio, $fechaFin])->orWhere('created_at', $fechaFin)->count();


        $cantTicketsCalificadosResponsable = CalificacionTicket::where('calificador_id', $idEmpleado)->where('solicitante_o_responsable', CalificacionTicket::RESPONSABLE)->whereBetween('created_at', [$fechaInicio, $fechaFin])->orWhere('created_at', $fechaFin)->count();
        $cantTicketsCalificadosSolicitante = CalificacionTicket::where('calificador_id', $idEmpleado)->where('solicitante_o_responsable', CalificacionTicket::SOLICITANTE)->whereBetween('created_at', [$fechaInicio, $fechaFin])->orWhere('created_at', $fechaFin)->count();
        // Listados
        // $ticketsFinalizados = $empleado->tickets()->whereIn('estado', [Ticket::FINALIZADO_SIN_SOLUCION, Ticket::FINALIZADO_SOLUCIONADO])->whereBetween('created_at', [$fechaInicio, $fechaFin])->orWhere('created_at', $fechaFin)->get();
        $ticketsFinalizados = $empleado->tickets()->whereBetween('created_at', [$fechaInicio, $fechaFin])->orWhere('created_at', $fechaFin)->get();
        $tiemposTicketsFinalizados = $this->mapearTickets($ticketsFinalizados);

        /*$tiemposTicketsFinalizados = $tiemposTicketsFinalizados->sort(function ($a, $b) {
            return $b['tiempo_hasta_finalizar_segundos'] - $a['tiempo_hasta_finalizar_segundos'];
        });*/

        $cantidadesTicketsSolicitadosPorDepartamento = $this->obtenerCantidadTicketsSolicitadosPorDepartamento();
        $cantidadesTicketsRecibidosPorDepartamento = $this->obtenerCantidadTicketsRecibidosPorDepartamento();
        $ticketsPorEstado = $this->obtenerTicketsPorEstado();

        $results = compact(
            'cantTicketsCreados',
            'cantTicketsRecibidos',
            'cantTicketsReasignados',
            'cantTicketsAsignados',
            'cantTicketsEjecutados',
            'cantTicketsPausados',
            'cantTicketsCalificadosResponsable',
            'cantTicketsCalificadosSolicitante',
            'cantTicketsFinalizadosSolucionados',
            'cantTicketsFinalizadosSinSolucion',
            'tiemposTicketsFinalizados',
            'cantidadesTicketsSolicitadosPorDepartamento',
            'cantidadesTicketsRecibidosPorDepartamento',
            'ticketsPorEstado',
        );

        return response()->json(compact('results'));
    }

    private function mapearTickets($tickets)
    {
        return $tickets->map(function ($ticket) {
            $solicitante = $ticket->solicitante;

            return [
                'codigo' => $ticket->codigo,
                'asunto' => $ticket->asunto,
                'estado' => $ticket->estado,
                'departamento_solicitante' => $solicitante->departamento->nombre,
                'empleado_solicitante' => Empleado::extraerNombresApellidos($solicitante),
                'tiempo_hasta_finalizar' => $this->calcularTiempoEfectivo($ticket),
                'tiempo_ocupado_pausas' => $this->calcularTiempoPausado($ticket),
                'tiempo_hasta_finalizar_segundos' => $this->calcularTiempoEfectivoSegundos($ticket),
            ];
        });
    }

    private function calcularTiempoEfectivo(Ticket $ticket)
    {
        return CarbonInterval::seconds(Carbon::parse($ticket->fecha_hora_finalizado)->diffInSeconds(Carbon::parse($ticket->fecha_hora_ejecucion)))->cascade()->forHumans();
    }

    private function calcularTiempoEfectivoSegundos(Ticket $ticket)
    {
        return Carbon::parse($ticket->fecha_hora_finalizado)->diffInSeconds(Carbon::parse($ticket->fecha_hora_ejecucion));
    }

    private function calcularTiempoPausado(Ticket $ticket)
    {
        $segundos = $ticket->pausasTicket()->sum(DB::raw('TIMESTAMPDIFF(SECOND, fecha_hora_pausa, fecha_hora_retorno)'));
        return CarbonInterval::seconds($segundos)->cascade()->forHumans();
    }

    private function obtenerCantidadTicketsSolicitadosPorDepartamento()
    {
        // Obtencion de parametros
        $idEmpleado = request('empleado_id');
        $fechaInicio = request('fecha_inicio');
        $fechaFin = request('fecha_fin');

        // Conversion de fechas
        $fechaInicio = Carbon::createFromFormat('d-m-Y', $fechaInicio)->format('Y-m-d');
        $fechaFin = Carbon::createFromFormat('d-m-Y', $fechaFin)->addDay()->toDateString();

        return Ticket::join('departamentos', 'tickets.departamento_responsable_id', '=', 'departamentos.id')
            ->select('departamentos.nombre', DB::raw('COUNT(*) as total'))
            ->where('solicitante_id', $idEmpleado)
            ->groupBy('departamentos.nombre')
            ->whereBetween('tickets.created_at', [$fechaInicio, $fechaFin])->orWhere('tickets.created_at', $fechaFin)
            ->get();
    }

    private function obtenerCantidadTicketsRecibidosPorDepartamento()
    {
        // Obtencion de parametros
        $idEmpleado = request('empleado_id');
        $fechaInicio = request('fecha_inicio');
        $fechaFin = request('fecha_fin');

        // Conversion de fechas
        $fechaInicio = Carbon::createFromFormat('d-m-Y', $fechaInicio)->format('Y-m-d');
        $fechaFin = Carbon::createFromFormat('d-m-Y', $fechaFin)->addDay()->toDateString();

        return Ticket::join('empleados as emp', 'tickets.solicitante_id', '=', 'emp.id')
            ->join('departamentos as dep', 'emp.departamento_id', '=', 'dep.id')
            ->where('tickets.responsable_id', $idEmpleado)
            ->whereBetween('tickets.created_at', [$fechaInicio, $fechaFin])->orWhere('tickets.created_at', $fechaFin)
            ->groupBy('dep.nombre')
            ->selectRaw('COUNT(tickets.codigo) as total, dep.nombre')
            ->get();
    }

    private function obtenerTicketsPorEstado()
    {
        $idEmpleado = request('empleado_id');
        $fechaInicio = request('fecha_inicio');
        $fechaFin = request('fecha_fin');

        // Conversion de fechas
        $fechaInicio = Carbon::createFromFormat('d-m-Y', $fechaInicio)->format('Y-m-d');
        $fechaFin = Carbon::createFromFormat('d-m-Y', $fechaFin)->addDay()->toDateString();

        return Ticket::select('estado', DB::raw('COUNT(*) as total_tickets'))
            ->where('responsable_id', $idEmpleado)
            ->whereBetween('created_at', [$fechaInicio, $fechaFin])->orWhere('created_at', $fechaFin)
            ->groupBy('estado')
            ->get();
    }

    /*private function obtenerCantidadTicketsPorEmpleado() {
        //
    }*/
}
