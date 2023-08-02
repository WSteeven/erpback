<?php

namespace App\Http\Controllers;

use App\Models\CalificacionTicket;
use App\Models\Empleado;
use App\Models\Ticket;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
        $recibidos = $empleado->tickets()->whereBetween('created_at', [$fechaInicio, $fechaFin])->orWhere('created_at', $fechaFin);
        $creados = $empleado->ticketsSolicitados()->where(function ($query) use ($fechaInicio, $fechaFin) {
            $query->whereBetween('created_at', [$fechaInicio, $fechaFin])->orWhere('created_at', $fechaFin);
        });

        /* $creadosParaMi = $empleado->ticketsSolicitados()->where(function ($query) use ($fechaInicio, $fechaFin) {
            $query->where('ticket_para_mi', 1)->whereBetween('created_at', [$fechaInicio, $fechaFin])->orWhere('created_at', $fechaFin);
        });*/

        $cantTicketsRecibidos = $recibidos->count();
        $cantTicketsCreados = $creados->count();
        $cantTicketsAsignados = $empleado->tickets()->where('estado', Ticket::ASIGNADO)->whereBetween('created_at', [$fechaInicio, $fechaFin])->orWhere('created_at', $fechaFin)->count();
        $cantTicketsReasignados = $empleado->tickets()->where('estado', Ticket::REASIGNADO)->whereBetween('created_at', [$fechaInicio, $fechaFin])->orWhere('created_at', $fechaFin)->count();
        $cantTicketsEjecutados = $empleado->tickets()->where('estado', Ticket::EJECUTANDO)->whereBetween('created_at', [$fechaInicio, $fechaFin])->orWhere('created_at', $fechaFin)->count();
        $cantTicketsCancelados = $empleado->tickets()->where('estado', Ticket::CANCELADO)->whereBetween('created_at', [$fechaInicio, $fechaFin])->orWhere('created_at', $fechaFin)->count();
        $cantTicketsPausados = $empleado->tickets()->where('estado', Ticket::PAUSADO)->whereBetween('created_at', [$fechaInicio, $fechaFin])->orWhere('created_at', $fechaFin)->count();

        $ticketsFinalizadosSolucionados = $empleado->tickets()->where('estado', Ticket::FINALIZADO_SOLUCIONADO)->whereBetween('created_at', [$fechaInicio, $fechaFin])->orWhere('created_at', $fechaFin)->get();
        $ticketsFinalizadosSinSolucion = $empleado->tickets()->where('estado', Ticket::FINALIZADO_SIN_SOLUCION)->whereBetween('created_at', [$fechaInicio, $fechaFin])->orWhere('created_at', $fechaFin)->get();

        $idsRecibidos = $recibidos->pluck('id')->toArray();
        $idsCreados = $creados->pluck('id')->toArray();

        // Restar a los tickets finalizados solucionados aquellos q fueron calificados por el responsable
        $idsTicketsCalificadosResponsable = CalificacionTicket::where('calificador_id', $idEmpleado)->where('solicitante_o_responsable', CalificacionTicket::RESPONSABLE)->whereIn('ticket_id', $idsRecibidos)->pluck('ticket_id')->toArray();
        $cantTicketsFinalizadosSolucionados = $ticketsFinalizadosSolucionados->filter(fn ($ticket) => !in_array($ticket->id, $idsTicketsCalificadosResponsable))->count();

        // Restar a los tickets finalizados sin solucion aquellos q fueron calificados por el responsable
        $cantTicketsFinalizadosSinSolucion = $ticketsFinalizadosSinSolucion->filter(fn ($ticket) => !in_array($ticket->id, $idsTicketsCalificadosResponsable))->count();
        /* Log::channel('testing')->info('Log', ['idsRecibidos', $idsRecibidos]);
        Log::channel('testing')->info('Log', ['idsTicketsCalificadosResponsable', $idsTicketsCalificadosResponsable]);
        Log::channel('testing')->info('Log', ['restaTicketsFinalizadosSolucionados', $cantTicketsFinalizadosSolucionados]);*/

        $cantTicketsCalificadosResponsable = CalificacionTicket::where('calificador_id', $idEmpleado)->where('solicitante_o_responsable', CalificacionTicket::RESPONSABLE)->whereIn('ticket_id', $idsRecibidos)->count();
        $cantTicketsCalificadosSolicitante = CalificacionTicket::where('calificador_id', $idEmpleado)->where('solicitante_o_responsable', CalificacionTicket::SOLICITANTE)->whereIn('ticket_id', $idsCreados)->count();

        // Listados
        $ticketsFinalizados = $empleado->tickets()->whereBetween('created_at', [$fechaInicio, $fechaFin])->orWhere('created_at', $fechaFin)->get();
        $tiemposTicketsFinalizados = $this->mapearTickets($ticketsFinalizados);

        $ticketsPorEstado = $this->ajustarCalificadosPorResponsable($this->obtenerTicketsPorEstado(), $cantTicketsCalificadosResponsable);
        $ticketsPorDepartamentoEstadoAsignado = $this->obtenerCantidadTicketsPorDepartamentoEstado(Ticket::ASIGNADO);
        $ticketsPorDepartamentoEstadoReasignado = $this->obtenerCantidadTicketsPorDepartamentoEstado(Ticket::REASIGNADO);
        $ticketsPorDepartamentoEstadoEjecutando = $this->obtenerCantidadTicketsPorDepartamentoEstado(Ticket::EJECUTANDO);
        $ticketsPorDepartamentoEstadoPausado = $this->obtenerCantidadTicketsPorDepartamentoEstado(Ticket::PAUSADO);
        $ticketsPorDepartamentoEstadoFinalizadoSolucionado = $this->obtenerCantidadTicketsPorDepartamentoEstado(Ticket::FINALIZADO_SOLUCIONADO);
        $ticketsPorDepartamentoEstadoFinalizadoSinSolucion = $this->obtenerCantidadTicketsPorDepartamentoEstado(Ticket::FINALIZADO_SIN_SOLUCION);
        $ticketsPorDepartamentoEstadoCalificado = $this->obtenerCantidadTicketsPorDepartamentoEstado(Ticket::CALIFICADO);

        $cantidadesTicketsSolicitadosPorDepartamento = $this->obtenerCantidadTicketsSolicitadosPorDepartamento();
        $cantidadesTicketsRecibidosPorDepartamento = $this->obtenerCantidadTicketsRecibidosPorDepartamento();

        $results = compact(
            'cantTicketsCreados',
            'cantTicketsRecibidos',
            'cantTicketsReasignados',
            'cantTicketsAsignados',
            'cantTicketsEjecutados',
            'cantTicketsCancelados',
            'cantTicketsPausados',
            'cantTicketsCalificadosResponsable',
            'cantTicketsCalificadosSolicitante',
            'cantTicketsFinalizadosSolucionados',
            'cantTicketsFinalizadosSinSolucion',
            'tiemposTicketsFinalizados',
            'cantidadesTicketsSolicitadosPorDepartamento',
            'cantidadesTicketsRecibidosPorDepartamento',
            'ticketsPorEstado',
            'ticketsPorDepartamentoEstadoAsignado',
            'ticketsPorDepartamentoEstadoReasignado',
            'ticketsPorDepartamentoEstadoEjecutando',
            'ticketsPorDepartamentoEstadoPausado',
            'ticketsPorDepartamentoEstadoFinalizadoSolucionado',
            'ticketsPorDepartamentoEstadoFinalizadoSinSolucion',
            'ticketsPorDepartamentoEstadoCalificado',
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
                'departamento_solicitante' => $solicitante->departamento?->nombre,
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

        return DB::table('tickets')->join('empleados as emp', 'tickets.solicitante_id', '=', 'emp.id')
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

    private function obtenerCantidadTicketsPorDepartamentoEstado($estado)
    {
        $fechaInicio = request('fecha_inicio');
        $fechaFin = request('fecha_fin');

        // Conversion de fechas
        $fechaInicio = Carbon::createFromFormat('d-m-Y', $fechaInicio)->format('Y-m-d');
        $fechaFin = Carbon::createFromFormat('d-m-Y', $fechaFin)->addDay()->toDateString();

        $departamentoResponsableId = request('departamento_responsable_id');

        return DB::table('tickets')->select(DB::raw("CONCAT(empleados.nombres, ' ', empleados.apellidos) AS responsable"), DB::raw('COUNT(tickets.codigo) as total_tickets'), 'tickets.estado')
            ->join('empleados', 'tickets.responsable_id', '=', 'empleados.id')
            ->where('departamento_responsable_id', $departamentoResponsableId)
            ->where('tickets.estado', $estado)
            ->whereBetween('tickets.created_at', [$fechaInicio, $fechaFin])->orWhere('tickets.created_at', $fechaFin)
            ->groupBy('responsable')
            ->get();
    }

    private function ajustarCalificadosPorResponsable($tickets, $cantTicketsCalificadosResponsable)
    {
        return $tickets->map(function ($ticket) use ($cantTicketsCalificadosResponsable) {
            if ($ticket->estado == Ticket::CALIFICADO) {
                $ticket['total_tickets'] = $cantTicketsCalificadosResponsable;
            }
            return $ticket;
        });
    }

    private function obtenerCantidadTicketsFinalizadosSolucionados()
    {
        //
    }
}
