<?php

namespace App\Http\Controllers;

use App\Http\Resources\TicketResource;
use App\Models\CalificacionTicket;
use App\Models\Empleado;
use App\Models\Ticket;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Src\App\DashboardTicketService;

class DashboardTicketController extends Controller
{
    private DashboardTicketService $service;

    public function __construct()
    {
        $this->service = new DashboardTicketService();
    }

    public function index()
    {
        if (request('empleado_id')) return $this->empleado();
        if (request('departamento_responsable_id')) return $this->departamento();
    }

    function calcularTiempoEfectivoTotal($ticket)
    {
        if (in_array($ticket->estado, [Ticket::FINALIZADO_SOLUCIONADO, Ticket::FINALIZADO_SIN_SOLUCION])) {
            $tiempos = $ticket->audits()->get(['auditable_id', 'user_id', 'new_values', 'created_at']);
            $primerEjecucion = $tiempos->first(fn ($tiempo) => $tiempo->new_values['estado'] === Ticket::EJECUTANDO);
            $finalizacion = $tiempos->first(fn ($tiempo) => isset($tiempo->new_values['estado']) ? ($tiempo->new_values['estado'] === Ticket::FINALIZADO_SOLUCIONADO || $tiempo->new_values['estado'] === Ticket::FINALIZADO_SIN_SOLUCION) : false);
            return $finalizacion ? Carbon::parse($finalizacion->new_values['fecha_hora_finalizado'])->diffInHours(Carbon::parse($primerEjecucion->new_values['fecha_hora_ejecucion'])) : null;
        } else {
            return null;
        }
    }

    function departamento()
    {
        // Graficos de pastel
        $temporal = $this->service->obtenerCantidadTicketsPorDepartamentoEstado(Ticket::ASIGNADO);
        $ticketsPorDepartamentoEstadoAsignado = TicketResource::collection($temporal);
        $ticketsPorDepartamentoEstadoReasignado = TicketResource::collection($this->service->obtenerCantidadTicketsPorDepartamentoEstado(Ticket::REASIGNADO));
        $ticketsPorDepartamentoEstadoEjecutando = TicketResource::collection($this->service->obtenerCantidadTicketsPorDepartamentoEstado(Ticket::EJECUTANDO));
        $ticketsPorDepartamentoEstadoPausado = TicketResource::collection($this->service->obtenerCantidadTicketsPorDepartamentoEstado(Ticket::PAUSADO));
        $ticketsPorDepartamentoEstadoFinalizadoSolucionado = TicketResource::collection($this->service->obtenerCantidadTicketsPorDepartamentoEstado(Ticket::FINALIZADO_SOLUCIONADO));
        $ticketsPorDepartamentoEstadoFinalizadoSinSolucion = TicketResource::collection($this->service->obtenerCantidadTicketsPorDepartamentoEstado(Ticket::FINALIZADO_SIN_SOLUCION));
        $ticketsPorDepartamentoEstadoCalificado = TicketResource::collection($this->service->obtenerCantidadTicketsPorDepartamentoEstado(Ticket::CALIFICADO));

        // $ticketsPorDepartamentoEstadoFinalizadoSolucionado = TicketResource::collection($this->service->obtenerCantidadTicketsPorDepartamentoEstado(Ticket::FINALIZADO_SOLUCIONADO));

        $tiempoPromedio = $ticketsPorDepartamentoEstadoFinalizadoSolucionado->map(fn ($tiempo) => $this->calcularTiempoEfectivoTotalSegundos($tiempo))->avg();
        $tiempoPromedio = CarbonInterval::seconds($tiempoPromedio)->cascade()->forHumans();
        $totalTicketsFinalizados = $ticketsPorDepartamentoEstadoFinalizadoSolucionado->count();

        $results = compact(
            // Graficos de pastel
            'ticketsPorDepartamentoEstadoAsignado',
            'ticketsPorDepartamentoEstadoReasignado',
            'ticketsPorDepartamentoEstadoEjecutando',
            'ticketsPorDepartamentoEstadoPausado',
            'ticketsPorDepartamentoEstadoFinalizadoSolucionado',
            'ticketsPorDepartamentoEstadoFinalizadoSinSolucion',
            'ticketsPorDepartamentoEstadoCalificado',
            // Lineas de tiempo
            'tiempoPromedio',
            'totalTicketsFinalizados',
            'ticketsPorDepartamentoEstadoFinalizadoSolucionado',
        );

        return response()->json(compact('results'));
    }

    function empleado()
    {
        // Obtencion de parametros
        $idEmpleado = request('empleado_id');
        $fechaInicio = request('fecha_inicio');
        $fechaFin = request('fecha_fin');

        // Busqueda de empleado
        $empleado = Empleado::find($idEmpleado);

        // Conversion de fechas
        $fechaInicio = Carbon::createFromFormat('d-m-Y', $fechaInicio)->format('Y-m-d');
        $fechaFin = Carbon::createFromFormat('d-m-Y', $fechaFin)->addDay()->toDateString();

        /************
         * Listados
         ************/
        $ticketsFinalizadosSolucionados = $empleado?->tickets()->where('estado', Ticket::FINALIZADO_SOLUCIONADO)->whereBetween('created_at', [$fechaInicio, $fechaFin])->orWhere('created_at', $fechaFin)->get();
        $ticketsFinalizadosSinSolucion = $empleado?->tickets()->where('estado', Ticket::FINALIZADO_SIN_SOLUCION)->whereBetween('created_at', [$fechaInicio, $fechaFin])->orWhere('created_at', $fechaFin)->get();

        $ticketsFinalizados = $empleado?->tickets()->whereBetween('created_at', [$fechaInicio, $fechaFin])->orWhere('created_at', $fechaFin)->get();
        $tiemposTicketsFinalizados = $this->mapearTickets($ticketsFinalizados);

        /*********************************************************
         * Consulta de tickets recibidos y solicitados (creados)
         *********************************************************/
        $recibidos = $empleado?->tickets()->whereBetween('created_at', [$fechaInicio, $fechaFin])->orWhere('created_at', $fechaFin);
        $creados = $empleado?->ticketsSolicitados()->where(function ($query) use ($fechaInicio, $fechaFin) {
            $query->whereBetween('created_at', [$fechaInicio, $fechaFin])->orWhere('created_at', $fechaFin);
        });

        // Obtencion de ids
        $idsCreados = $creados?->pluck('id')->toArray();
        $idsRecibidos = $recibidos?->pluck('id')->toArray();

        /***************************
         * Grupo de Tickets creados
         ***************************/

        $cantTicketsCreados = $creados->count();
        $cantTicketsCreadosParaMi = $empleado?->ticketsSolicitados()->where(function ($query) use ($fechaInicio, $fechaFin) {
            $query->where('ticket_para_mi', 1)->whereNot('estado', Ticket::CANCELADO)->whereBetween('created_at', [$fechaInicio, $fechaFin])->orWhere('created_at', $fechaFin);
        })->count();
        $cantTicketsCreadosInternos = $empleado?->ticketsSolicitados()->where(function ($query) use ($fechaInicio, $fechaFin) {
            $query->where('ticket_interno', 1)->whereNot('estado', Ticket::CANCELADO)->whereBetween('created_at', [$fechaInicio, $fechaFin])->orWhere('created_at', $fechaFin);
        })->count();
        $cantTicketsCreadosADepartamentos = $empleado?->ticketsSolicitados()->where(function ($query) use ($fechaInicio, $fechaFin) {
            $query->where('ticket_interno', 0)->whereNot('estado', Ticket::CANCELADO)->where('ticket_para_mi', 0)->whereBetween('created_at', [$fechaInicio, $fechaFin])->orWhere('created_at', $fechaFin);
        })->count();
        $cantTicketsCanceladosPorMi = $empleado?->ticketsSolicitados()->where('estado', Ticket::CANCELADO)->whereBetween('created_at', [$fechaInicio, $fechaFin])->orWhere('created_at', $fechaFin)->count();
        $cantTicketsCalificadosSolicitante = CalificacionTicket::where('calificador_id', $idEmpleado)->where('solicitante_o_responsable', CalificacionTicket::SOLICITANTE)->whereIn('ticket_id', $idsCreados)->count();

        /*****************************
         * Grupo de Tickets recibidos
         *****************************/
        $cantTicketsRecibidos = $recibidos->count();
        $cantTicketsAsignados = $empleado?->tickets()->where('estado', Ticket::ASIGNADO)->whereBetween('created_at', [$fechaInicio, $fechaFin])->orWhere('created_at', $fechaFin)->count();
        $cantTicketsCancelados = $empleado?->tickets()->where('estado', Ticket::CANCELADO)->whereBetween('created_at', [$fechaInicio, $fechaFin])->orWhere('created_at', $fechaFin)->count();
        $cantTicketsReasignados = $empleado?->tickets()->where('estado', Ticket::REASIGNADO)->whereBetween('created_at', [$fechaInicio, $fechaFin])->orWhere('created_at', $fechaFin)->count();
        $cantTicketsEjecutados = $empleado?->tickets()->where('estado', Ticket::EJECUTANDO)->whereBetween('created_at', [$fechaInicio, $fechaFin])->orWhere('created_at', $fechaFin)->count();
        $cantTicketsPausados = $empleado?->tickets()->where('estado', Ticket::PAUSADO)->whereBetween('created_at', [$fechaInicio, $fechaFin])->orWhere('created_at', $fechaFin)->count();
        $cantTicketsFinalizadosSolucionados = $empleado?->tickets()->where('estado', Ticket::FINALIZADO_SOLUCIONADO)->whereBetween('created_at', [$fechaInicio, $fechaFin])->orWhere('created_at', $fechaFin)->count(); //$this->calcularCantidadTicketFinalizadosSolucionados($idsRecibidos);//
        $cantTicketsFinalizadosSinSolucion = $empleado?->tickets()->where('estado', Ticket::FINALIZADO_SIN_SOLUCION)->whereBetween('created_at', [$fechaInicio, $fechaFin])->orWhere('created_at', $fechaFin)->count();
        $cantTicketsCalificadosResponsable = CalificacionTicket::where('calificador_id', $idEmpleado)->where('solicitante_o_responsable', CalificacionTicket::RESPONSABLE)->whereIn('ticket_id', $idsRecibidos)->count();

        $ticketsPorEstado = TicketResource::collection($this->obtenerTicketsPorEstado()); //$this->ajustarEstadosPorEstado($this->obtenerTicketsPorEstado());

        // nuevo
        $ticketsCreadosADepartamentos = TicketResource::collection($this->service->obtenerCantidadTicketsSolicitadosPorDepartamento());
        $ticketsRecibidosPorDepartamentos = TicketResource::collection($this->service->obtenerCantidadTicketsRecibidosPorDepartamento());

        $results = compact(
            'cantTicketsCreados',
            'cantTicketsCreadosParaMi',
            'cantTicketsCreadosInternos',
            'cantTicketsCreadosADepartamentos',
            'cantTicketsRecibidos',
            'cantTicketsReasignados',
            'cantTicketsAsignados',
            'cantTicketsEjecutados',
            'cantTicketsCancelados',
            'cantTicketsCanceladosPorMi',
            'cantTicketsPausados',
            'cantTicketsCalificadosResponsable',
            'cantTicketsCalificadosSolicitante',
            'cantTicketsFinalizadosSolucionados',
            'cantTicketsFinalizadosSinSolucion',
            'ticketsCreadosADepartamentos',
            'ticketsRecibidosPorDepartamentos',
            // Listados
            'ticketsPorEstado',
            'creados',
        );

        return response()->json(compact('results'));
    }

    function calcularTiempoEfectivoTotalSegundos($ticket)
    {
        if (in_array($ticket->estado, [Ticket::FINALIZADO_SOLUCIONADO, Ticket::FINALIZADO_SIN_SOLUCION])) {
            $tiempos = $ticket->audits()->get(['auditable_id', 'user_id', 'new_values', 'created_at']);


            if ($tiempos) {

                $primerEjecucion = $tiempos->first(fn ($tiempo) => $tiempo->new_values && isset($tiempo->new_values['estado']) ? $tiempo->new_values['estado'] === Ticket::EJECUTANDO : null);

                if ($primerEjecucion) {
                    $finalizacion = $tiempos->first(fn ($tiempo) => isset($tiempo->new_values['estado']) ? ($tiempo->new_values['estado'] === Ticket::FINALIZADO_SOLUCIONADO || $tiempo->new_values['estado'] === Ticket::FINALIZADO_SIN_SOLUCION) : false);
                    return $finalizacion ? Carbon::parse($finalizacion->new_values['fecha_hora_finalizado'])->diffInSeconds(Carbon::parse($primerEjecucion->new_values['fecha_hora_ejecucion'])) : null;
                }
            } else {
                return null;
            }
        } else {
            return null;
        }
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

    private function obtenerTicketsPorEstado()
    {
        $idEmpleado = request('empleado_id');
        $fechaInicio = request('fecha_inicio');
        $fechaFin = request('fecha_fin');

        // Conversion de fechas
        $fechaInicio = Carbon::createFromFormat('d-m-Y', $fechaInicio)->format('Y-m-d');
        $fechaFin = Carbon::createFromFormat('d-m-Y', $fechaFin)->addDay()->toDateString();

        /* return Ticket::select('estado', DB::raw('COUNT(*) as total_tickets'))
            ->where('responsable_id', $idEmpleado)
            ->whereBetween('created_at', [$fechaInicio, $fechaFin])->orWhere('created_at', $fechaFin)
            ->groupBy('estado')
            ->get(); */

        return Ticket::where('responsable_id', $idEmpleado)
            ->whereBetween('created_at', [$fechaInicio, $fechaFin])->orWhere('created_at', $fechaFin)
            ->get();
    }

    // hueso carnudo - 1libra


    private function obtenerCantidadTicketsPorDepartamentoEstadoOld($estado)
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

    // Se elimina el concepto de FINALIZADO SOLUCIONADO, FINALIZADO SIN SOLUCION Y CALIFICADOS y se reemplaza simplemente por FINALIZADOS
    private function ajustarEstadosPorEstado($tickets)
    {
        $sumaTicketsFinalizados = $tickets->filter(function ($ticket) {
            return in_array($ticket->estado, [Ticket::FINALIZADO_SOLUCIONADO, Ticket::FINALIZADO_SIN_SOLUCION, Ticket::CALIFICADO]);
        })->sum('total_tickets');

        $tickets = $tickets->map(function ($ticket) {
            if ($ticket->estado == Ticket::ASIGNADO) {
                $ticket['estado'] = 'PENDIENTE';
            }
            return $ticket;
        });

        $tickets = $tickets->filter(function ($ticket) {
            return !in_array($ticket->estado, [Ticket::FINALIZADO_SOLUCIONADO, Ticket::FINALIZADO_SIN_SOLUCION, Ticket::CALIFICADO]);
        });

        $tickets->push([
            'estado' => 'FINALIZADO',
            'total_tickets' => $sumaTicketsFinalizados,
        ]);

        return array_values($tickets->toArray());
    }

    private function calcularCantidadTicketFinalizadosSolucionados(array $idsRecibidos)
    {
        $empleado = Empleado::find(request('empleado_id'));
        $fechaInicio = request('fecha_inicio');
        $fechaFin = request('fecha_fin');

        // Conversion de fechas
        $fechaInicio = Carbon::createFromFormat('d-m-Y', $fechaInicio)->format('Y-m-d');
        $fechaFin = Carbon::createFromFormat('d-m-Y', $fechaFin)->addDay()->toDateString();

        $idsTicketsFinalizadosSolucionados = $empleado->tickets()->where('estado', Ticket::FINALIZADO_SOLUCIONADO)->whereBetween('created_at', [$fechaInicio, $fechaFin])->orWhere('created_at', $fechaFin)->pluck('id')->toArray();
        $idsTicketsCalificadosResponsable = CalificacionTicket::where('calificador_id', $empleado->id)->where('solicitante_o_responsable', CalificacionTicket::RESPONSABLE)->whereIn('ticket_id', $idsRecibidos)->pluck('ticket_id')->toArray();

        $coincidencias = array_values(array_diff($idsTicketsFinalizadosSolucionados, $idsTicketsCalificadosResponsable));
        Log::channel('testing')->info('Log', compact('coincidencias'));
        Log::channel('testing')->info('Log', compact('idsTicketsFinalizadosSolucionados'));
        Log::channel('testing')->info('Log', compact('idsTicketsCalificadosResponsable'));
        return count($idsTicketsFinalizadosSolucionados) + count($coincidencias);
    }
}
