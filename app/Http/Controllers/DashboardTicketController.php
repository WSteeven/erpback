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

        /*********************************************************
         * Consulta de tickets recibidos y solicitados (creados)
         *********************************************************/
        $recibidos = $empleado->tickets()->whereBetween('created_at', [$fechaInicio, $fechaFin])->orWhere('created_at', $fechaFin);
        $creados = $empleado->ticketsSolicitados()->where(function ($query) use ($fechaInicio, $fechaFin) {
            $query->whereBetween('created_at', [$fechaInicio, $fechaFin])->orWhere('created_at', $fechaFin);
        });
        // Log::channel('testing')->info('Log', compact('creados'));

        // Obtencion de ids
        $idsCreados = $creados->pluck('id')->toArray();
        $idsRecibidos = $recibidos->pluck('id')->toArray();

        /***************************
         * Grupo de Tickets creados
         ***************************/
        $cantTicketsCreados = $creados->count();
        $cantTicketsCreadosParaMi = $empleado->ticketsSolicitados()->where(function ($query) use ($fechaInicio, $fechaFin) {
            $query->where('ticket_para_mi', 1)->whereNot('estado', Ticket::CANCELADO)->whereBetween('created_at', [$fechaInicio, $fechaFin])->orWhere('created_at', $fechaFin);
        })->count();
        $cantTicketsCreadosInternos = $empleado->ticketsSolicitados()->where(function ($query) use ($fechaInicio, $fechaFin) {
            $query->where('ticket_interno', 1)->whereNot('estado', Ticket::CANCELADO)->whereBetween('created_at', [$fechaInicio, $fechaFin])->orWhere('created_at', $fechaFin);
        })->count();
        $cantTicketsCreadosADepartamentos = $empleado->ticketsSolicitados()->where(function ($query) use ($fechaInicio, $fechaFin) {
            $query->where('ticket_interno', 0)->whereNot('estado', Ticket::CANCELADO)->where('ticket_para_mi', 0)->whereBetween('created_at', [$fechaInicio, $fechaFin])->orWhere('created_at', $fechaFin);
        })->count();
        $cantTicketsCanceladosPorMi = $empleado->ticketsSolicitados()->where('estado', Ticket::CANCELADO)->whereBetween('created_at', [$fechaInicio, $fechaFin])->orWhere('created_at', $fechaFin)->count();
        $cantTicketsCalificadosSolicitante = CalificacionTicket::where('calificador_id', $idEmpleado)->where('solicitante_o_responsable', CalificacionTicket::SOLICITANTE)->whereIn('ticket_id', $idsCreados)->count();

        /*****************************
         * Grupo de Tickets recibidos
         *****************************/
        $cantTicketsRecibidos = $recibidos->count();
        $cantTicketsAsignados = $empleado->tickets()->where('estado', Ticket::ASIGNADO)->whereBetween('created_at', [$fechaInicio, $fechaFin])->orWhere('created_at', $fechaFin)->count();
        $cantTicketsCancelados = $empleado->tickets()->where('estado', Ticket::CANCELADO)->whereBetween('created_at', [$fechaInicio, $fechaFin])->orWhere('created_at', $fechaFin)->count();
        $cantTicketsReasignados = $empleado->tickets()->where('estado', Ticket::REASIGNADO)->whereBetween('created_at', [$fechaInicio, $fechaFin])->orWhere('created_at', $fechaFin)->count();
        $cantTicketsEjecutados = $empleado->tickets()->where('estado', Ticket::EJECUTANDO)->whereBetween('created_at', [$fechaInicio, $fechaFin])->orWhere('created_at', $fechaFin)->count();
        $cantTicketsPausados = $empleado->tickets()->where('estado', Ticket::PAUSADO)->whereBetween('created_at', [$fechaInicio, $fechaFin])->orWhere('created_at', $fechaFin)->count();
        $cantTicketsFinalizadosSolucionados = $empleado->tickets()->where('estado', Ticket::FINALIZADO_SOLUCIONADO)->whereBetween('created_at', [$fechaInicio, $fechaFin])->orWhere('created_at', $fechaFin)->count(); //$this->calcularCantidadTicketFinalizadosSolucionados($idsRecibidos);//
        $cantTicketsFinalizadosSinSolucion = $empleado->tickets()->where('estado', Ticket::FINALIZADO_SIN_SOLUCION)->whereBetween('created_at', [$fechaInicio, $fechaFin])->orWhere('created_at', $fechaFin)->count();
        $cantTicketsCalificadosResponsable = CalificacionTicket::where('calificador_id', $idEmpleado)->where('solicitante_o_responsable', CalificacionTicket::RESPONSABLE)->whereIn('ticket_id', $idsRecibidos)->count();

        /************
         * Listados
         ************/
        $ticketsFinalizadosSolucionados = $empleado->tickets()->where('estado', Ticket::FINALIZADO_SOLUCIONADO)->whereBetween('created_at', [$fechaInicio, $fechaFin])->orWhere('created_at', $fechaFin)->get();
        $ticketsFinalizadosSinSolucion = $empleado->tickets()->where('estado', Ticket::FINALIZADO_SIN_SOLUCION)->whereBetween('created_at', [$fechaInicio, $fechaFin])->orWhere('created_at', $fechaFin)->get();

        $ticketsFinalizados = $empleado->tickets()->whereBetween('created_at', [$fechaInicio, $fechaFin])->orWhere('created_at', $fechaFin)->get();
        $tiemposTicketsFinalizados = $this->mapearTickets($ticketsFinalizados);

        $ticketsPorEstado = TicketResource::collection($this->obtenerTicketsPorEstado()); //$this->ajustarEstadosPorEstado($this->obtenerTicketsPorEstado());
        $ticketsPorDepartamentoEstadoAsignado = $this->obtenerCantidadTicketsPorDepartamentoEstado(Ticket::ASIGNADO);
        $ticketsPorDepartamentoEstadoReasignado = $this->obtenerCantidadTicketsPorDepartamentoEstado(Ticket::REASIGNADO);
        $ticketsPorDepartamentoEstadoEjecutando = $this->obtenerCantidadTicketsPorDepartamentoEstado(Ticket::EJECUTANDO);
        $ticketsPorDepartamentoEstadoPausado = $this->obtenerCantidadTicketsPorDepartamentoEstado(Ticket::PAUSADO);
        $ticketsPorDepartamentoEstadoFinalizadoSolucionado = $this->obtenerCantidadTicketsPorDepartamentoEstado(Ticket::FINALIZADO_SOLUCIONADO);
        $ticketsPorDepartamentoEstadoFinalizadoSinSolucion = $this->obtenerCantidadTicketsPorDepartamentoEstado(Ticket::FINALIZADO_SIN_SOLUCION);
        $ticketsPorDepartamentoEstadoCalificado = $this->obtenerCantidadTicketsPorDepartamentoEstado(Ticket::CALIFICADO);

        // nuevo
        $ticketsCreadosADepartamentos = TicketResource::collection($this->obtenerCantidadTicketsSolicitadosPorDepartamento());
        $ticketsRecibidosPorDepartamentos = TicketResource::collection($this->obtenerCantidadTicketsRecibidosPorDepartamento());

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
            'tiemposTicketsFinalizados',
            'ticketsCreadosADepartamentos',
            'ticketsRecibidosPorDepartamentos',
            // Listados
            'ticketsPorEstado',
            'creados',
            // 'ticketsCreadosDepartamentos',
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

        /* return Ticket::join('departamentos', 'tickets.departamento_responsable_id', '=', 'departamentos.id')
            ->select('departamentos.nombre', DB::raw('COUNT(*) as total'))
            ->where('solicitante_id', $idEmpleado)
            ->groupBy('departamentos.nombre')
            ->whereBetween('tickets.created_at', [$fechaInicio, $fechaFin])->orWhere('tickets.created_at', $fechaFin)
            ->get(); */

        return Ticket::where('solicitante_id', $idEmpleado)
            ->whereBetween('tickets.created_at', [$fechaInicio, $fechaFin])->orWhere('tickets.created_at', $fechaFin)
            ->get();
        // ->select('departamentos.nombre', DB::raw('COUNT(*) as total'))
        // ->groupBy('departamentos.nombre')
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

        /*return DB::table('tickets')->join('empleados as emp', 'tickets.solicitante_id', '=', 'emp.id')
            ->join('departamentos as dep', 'emp.departamento_id', '=', 'dep.id')
            ->where('tickets.responsable_id', $idEmpleado)
            ->whereBetween('tickets.created_at', [$fechaInicio, $fechaFin])->orWhere('tickets.created_at', $fechaFin)
            ->groupBy('dep.nombre')
            ->selectRaw('COUNT(tickets.codigo) as total, dep.nombre')
            ->get();*/

        $res =  Ticket::where('responsable_id', $idEmpleado)
            ->whereBetween('tickets.created_at', [$fechaInicio, $fechaFin])->orWhere('tickets.created_at', $fechaFin)
            ->get();

            Log::channel('testing')->info('Log', compact('res'));
            return $res;
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
