<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use App\Models\Ticket;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

//->whereBetween('tickets.created_at', [$fechaInicio, $fechaFin])
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
        $fechaFin = Carbon::createFromFormat('d-m-Y', $fechaFin)->format('Y-m-d');

        // Busqueda de empleado
        $empleado = Empleado::find($idEmpleado);

        // Cosulta de datos
        $cantTicketsCreados = $empleado->ticketsSolicitados()->whereBetween('created_at', [$fechaInicio, $fechaFin])->count();
        $cantTicketsRecibidos = $empleado->tickets()->whereBetween('created_at', [$fechaInicio, $fechaFin])->count();
        $cantTicketsFinalizadosSolucionados = $empleado->tickets()->where('estado', Ticket::FINALIZADO_SOLUCIONADO)->whereBetween('created_at', [$fechaInicio, $fechaFin])->count();
        $cantTicketsFinalizadosSinSolucion = $empleado->tickets()->where('estado', Ticket::FINALIZADO_SIN_SOLUCION)->whereBetween('created_at', [$fechaInicio, $fechaFin])->count();

        // Listados
        $ticketsFinalizados = $empleado->tickets()->whereIn('estado', [Ticket::FINALIZADO_SIN_SOLUCION, Ticket::FINALIZADO_SOLUCIONADO])->whereBetween('created_at', [$fechaInicio, $fechaFin])->get();
        $tiemposTicketsFinalizados = $this->mapearTickets($ticketsFinalizados);
        /*$tiemposTicketsFinalizados = $tiemposTicketsFinalizados->sort(function ($a, $b) {
            return $b['tiempo_hasta_finalizar_segundos'] - $a['tiempo_hasta_finalizar_segundos'];
        });*/

        $cantidadesTicketsSolicitadosPorDepartamento = $this->obtenerCantidadTicketsSolicitadosPorDepartamento();
        $cantidadesTicketsRecibidosPorDepartamento = $this->obtenerCantidadTicketsRecibidosPorDepartamento();

        $results = compact(
            'cantTicketsCreados',
            'cantTicketsRecibidos',
            'cantTicketsFinalizadosSolucionados',
            'cantTicketsFinalizadosSinSolucion',
            'tiemposTicketsFinalizados',
            'cantidadesTicketsSolicitadosPorDepartamento',
            'cantidadesTicketsRecibidosPorDepartamento',
        );

        return response()->json(compact('results'));
    }

    private function mapearTickets($tickets)
    {
        return $tickets->map(fn ($ticket) => [
            'codigo' => $ticket->codigo,
            'asunto' => $ticket->asunto,
            'estado' => $ticket->estado,
            'tiempo_hasta_finalizar' => $this->calcularTiempoEfectivo($ticket),
            'tiempo_ocupado_pausas' => $this->calcularTiempoPausado($ticket),
            'tiempo_hasta_finalizar_segundos' => $this->calcularTiempoEfectivoSegundos($ticket),
        ]);
    }

    private function mapearTickets2($tickets)
    {
        return $tickets->map(function ($ticket) {
            $ticket['codigo'] = $ticket->codigo;
            $ticket['asunto'] = $ticket->asunto;
            $ticket['tiempo_hasta_finalizar'] = $this->calcularTiempoEfectivo($ticket);
            $ticket['tiempo_ocupado_pausas'] = $this->calcularTiempoPausado($ticket);
            $ticket['tiempo_hasta_finalizar_segundos'] = $this->calcularTiempoEfectivoSegundos($ticket);
            return $ticket;
        })->sortBy('tiempo_hasta_finalizar_segundos');;
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
        $fechaFin = Carbon::createFromFormat('d-m-Y', $fechaFin)->format('Y-m-d');

        return Ticket::join('departamentos', 'tickets.departamento_responsable_id', '=', 'departamentos.id')
            ->select('departamentos.nombre', DB::raw('COUNT(*) as total'))
            ->where('solicitante_id', $idEmpleado)
            ->groupBy('departamentos.nombre')
            ->whereBetween('tickets.created_at', [$fechaInicio, $fechaFin])
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
        $fechaFin = Carbon::createFromFormat('d-m-Y', $fechaFin)->format('Y-m-d');

        return Ticket::join('departamentos', 'tickets.departamento_responsable_id', '=', 'departamentos.id')
            ->select('departamentos.nombre', DB::raw('COUNT(*) as total'))
            ->where('tickets.responsable_id', $idEmpleado)
            ->groupBy('departamentos.nombre')
            ->whereBetween('tickets.created_at', [$fechaInicio, $fechaFin])
            ->get();
    }
}
