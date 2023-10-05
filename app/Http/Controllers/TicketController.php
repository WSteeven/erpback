<?php

namespace App\Http\Controllers;

use App\Events\ActualizarNotificacionesEvent;
use App\Events\TicketEvent;
use App\Http\Requests\TicketRequest;
use App\Http\Resources\TicketResource;
use App\Models\ActividadRealizadaSeguimientoTicket;
use App\Models\CalificacionTicket;
use App\Models\Empleado;
use App\Models\Ticket;
use App\Models\TicketRechazado;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Src\App\TicketService;
use Src\Shared\Utils;

class TicketController extends Controller
{
    private TicketService $servicio;
    private $entidad = 'Ticket';

    public function __construct()
    {
        $this->servicio = new TicketService();
    }

    public function index()
    {
        $results = Ticket::filter()->latest()->get();
        $results = TicketResource::collection($results);
        return response()->json(compact('results'));
    }

    public function store(TicketRequest $request)
    {
        // Adaptacion de foreign keys
        $datos = $request->validated();
        $datos['codigo'] = 'TCKT-' . (Ticket::count() == 0 ? 1 : Ticket::latest('id')->first()->id + 1);
        $datos['responsable_id'] = $request->safe()->only(['responsable'])['responsable'];
        $datos['solicitante_id'] = Auth::user()->empleado->id;
        $datos['tipo_ticket_id'] = $request->safe()->only(['tipo_ticket'])['tipo_ticket'];
        $datos['departamento_responsable_id'] = $request->safe()->only(['departamento_responsable'])['departamento_responsable'];
        $datos['ticket_para_mi'] = $request->safe()->only(['ticket_para_mi'])['ticket_para_mi'];

        // Calcular estados
        $datos['estado'] = Ticket::ASIGNADO;

        $ticket = Ticket::create($datos);

        $modelo = new TicketResource($ticket->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');

        event(new TicketEvent($ticket, $modelo->solicitante_id, $modelo->responsable_id));

        return response()->json(compact('mensaje', 'modelo'));
    }

    public function show(Ticket $ticket)
    {
        $modelo = new TicketResource($ticket);
        return response()->json(compact('modelo'));
    }

    public function cancelar(Request $request, Ticket $ticket)
    {
        $motivo_cancelado_ticket_id = $request['motivo_cancelado_ticket_id'];

        $ticket->estado = Ticket::CANCELADO;
        $ticket->fecha_hora_cancelado = Carbon::now();
        $ticket->motivo_cancelado_ticket_id = $motivo_cancelado_ticket_id;
        $ticket->save();

        $modelo = new TicketResource($ticket->refresh());
        $mensaje = 'Ticket cancelado exitosamente!';
        return response()->json(compact('modelo', 'mensaje'));
    }

    public function cambiarResponsable(Request $request, Ticket $ticket)
    {
        $request->validate([
            'departamento_responsable' => 'required|numeric|integer',
            'responsable' => 'required|numeric|integer',
        ]);

        $idResponsableAnterior = $ticket->responsable_id;

        $ticket->departamento_responsable_id = $request['departamento_responsable'];
        $ticket->responsable_id = $request['responsable'];
        $ticket->estado = Ticket::REASIGNADO;
        $ticket->save();

        $modelo = new TicketResource($ticket->refresh());
        $mensaje = 'Responsable cambiado exitosamente!';

        if (Auth::user()->empleado->id == $idResponsableAnterior) {
            // event(new TicketEvent($ticket, $idResponsableAnterior, $ticket->responsable_id));
            ActividadRealizadaSeguimientoTicket::create([
                'ticket_id' => $ticket->id,
                'fecha_hora' => Carbon::now(),
                'observacion' => 'TICKET TRANSFERIDO',
                'actividad_realizada' => Empleado::extraerNombresApellidos(Empleado::find($idResponsableAnterior)) . ' le ha transferido el ticket a ' . Empleado::extraerNombresApellidos($ticket->responsable) . '.',
            ]);

            event(new TicketEvent($ticket, $idResponsableAnterior, $modelo->responsable_id));
            event(new ActualizarNotificacionesEvent());
        } else {
            event(new TicketEvent($ticket, $modelo->solicitante_id, $modelo->responsable_id));
            event(new ActualizarNotificacionesEvent());
        }

        return response()->json(compact('modelo', 'mensaje'));
    }

    public function ejecutar(Request $request, Ticket $ticket)
    {
        $ticket->estado = Ticket::EJECUTANDO;
        $ticket->fecha_hora_ejecucion = Carbon::now();
        $ticket->save();

        $modelo = new TicketResource($ticket->refresh());
        $mensaje = 'Ticket ejecutado exitosamente!';

        event(new TicketEvent($ticket, $modelo->responsable_id, $modelo->solicitante_id));
        event(new ActualizarNotificacionesEvent());

        return response()->json(compact('modelo', 'mensaje'));
    }

    public function pausar(Request $request, Ticket $ticket)
    {
        $motivo_pausa_id = $request['motivo_pausa_ticket_id'];
        $ticket->estado = Ticket::PAUSADO;
        $ticket->save();

        $ticket->pausasTicket()->create([
            'fecha_hora_pausa' => Carbon::now(),
            'motivo_pausa_ticket_id' => $motivo_pausa_id,
            'responsable_id' => Auth::user()->empleado->id,
        ]);

        ActividadRealizadaSeguimientoTicket::create([
            'ticket_id' => $ticket->id,
            'fecha_hora' => Carbon::now(),
            'observacion' => '« SISTEMA »',
            'actividad_realizada' => '»»» ' . Empleado::extraerNombresApellidos($ticket->responsable) . ' ha pausado el ticket.',
        ]);

        $modelo = new TicketResource($ticket->refresh());
        $mensaje = 'Ticket pausado exitosamente!';

        event(new TicketEvent($ticket, $modelo->responsable_id, $modelo->solicitante_id));
        event(new ActualizarNotificacionesEvent());

        // event(new SubtareaEvent($subtarea, User::ROL_COORDINADOR));
        return response()->json(compact('modelo', 'mensaje'));
    }

    public function reanudar(Request $request, Ticket $ticket)
    {
        $ticket->estado = Ticket::EJECUTANDO;
        $ticket->save();

        $pausa = $ticket->pausasTicket()->orderBy('fecha_hora_pausa', 'desc')->first();
        $pausa->fecha_hora_retorno = Carbon::now();
        $pausa->save();

        $modelo = new TicketResource($ticket->refresh());
        $mensaje = 'Ticket reanudado exitosamente!';

        event(new TicketEvent($ticket, $modelo->responsable_id, $modelo->solicitante_id));
        event(new ActualizarNotificacionesEvent());

        //event(new SubtareaEvent($subtarea, User::ROL_COORDINADOR));
        return response()->json(compact('modelo', 'mensaje'));
    }

    public function finalizar(Ticket $ticket)
    {
        $this->servicio->puedeFinalizar($ticket);

        $ticket->estado = Ticket::FINALIZADO_SOLUCIONADO;
        $ticket->fecha_hora_finalizado = Carbon::now();
        $ticket->save();

        $modelo = new TicketResource($ticket->refresh());
        $mensaje = 'Ticket finalizado exitosamente!';

        event(new TicketEvent($ticket, $modelo->responsable_id, $modelo->solicitante_id));
        event(new ActualizarNotificacionesEvent());

        return response()->json(compact('modelo', 'mensaje'));
    }

    public function finalizarNoSolucion(Request $request, Ticket $ticket)
    {
        $request->validate([
            'motivo' => 'required',
        ]);

        $this->servicio->puedeFinalizar($ticket);

        $ticket->estado = Ticket::FINALIZADO_SIN_SOLUCION;
        $ticket->fecha_hora_finalizado = Carbon::now();
        $ticket->motivo_ticket_no_solucionado = $request['motivo'];
        $ticket->save();

        $modelo = new TicketResource($ticket->refresh());
        $mensaje = 'Ticket finalizado exitosamente!';

        event(new TicketEvent($ticket, $modelo->responsable_id, $modelo->solicitante_id));
        event(new ActualizarNotificacionesEvent());

        return response()->json(compact('modelo', 'mensaje'));
    }

    public function rechazar(Request $request, Ticket $ticket)
    {
        $request->validate([
            'motivo' => 'required',
        ]);

        $idResponsableAnterior = $ticket->responsable_id;

        $ticket->estado = Ticket::RECHAZADO;
        $ticket->responsable_id = NULL;
        $ticket->departamento_responsable_id = NULL;
        $ticket->save();


        TicketRechazado::create([
            'fecha_hora' => Carbon::now(),
            'motivo' => $request['motivo'],
            'responsable_id' => Auth::user()->empleado->id,
            'ticket_id' => $ticket->id,
        ]);

        $modelo = new TicketResource($ticket->refresh());
        $mensaje = 'Ticket rechazado exitosamente!';

        event(new TicketEvent($ticket, $idResponsableAnterior, $ticket->solicitante_id));
        event(new ActualizarNotificacionesEvent());

        return response()->json(compact('modelo', 'mensaje'));
    }

    public function calificar(Request $request, Ticket $ticket)
    {
        $request->validate([
            'observacion' => 'required|string',
            'solicitante_o_responsable' => 'required|string',
            'calificacion' => 'required|numeric|integer',
        ]);

        $ticket->calificacionesTickets()->create([
            'solicitante_o_responsable' => $request['solicitante_o_responsable'],
            'observacion' => $request['observacion'],
            'calificacion' => $request['calificacion'],
            'calificador_id' => Auth::user()->empleado->id,
            'ticket_id' => $ticket->id,
        ]);

        /*if ($this->ticketCalificado($ticket)) {
            $ticket->estado = Ticket::CALIFICADO;
            $ticket->save();
        }*/

        $modelo = new TicketResource($ticket->refresh());
        $mensaje = 'Ticket calificado exitosamente!';


        /* if ($request['solicitante_o_responsable'] === CalificacionTicket::SOLICITANTE)
            event(new TicketEvent($ticket, $modelo->solicitante_id, $modelo->responsable_id));
        else
            event(new TicketEvent($ticket, $modelo->responsable_id, $modelo->solicitante_id)); */

        return response()->json(compact('modelo', 'mensaje'));
    }

    public function ticketCalificado(Ticket $ticket)
    {
        return $ticket->calificacionesTickets->count() == 2;
    }

    /*******************
     * Obtener listados
     *******************/
    public function obtenerRechazados(Ticket $ticket)
    {
        $results = $ticket->ticketsRechazados->map(fn ($item) => [
            'fecha_hora' => $item->fecha_hora,
            'motivo' => $item->motivo,
            'responsable' => Empleado::extraerNombresApellidos($item->responsable),
        ]);

        return response()->json(compact('results'));
    }

    public function obtenerPausas(Ticket $ticket)
    {
        $results = $ticket->pausasTicket->map(fn ($item) => [
            'fecha_hora_pausa' => $item->fecha_hora_pausa,
            'fecha_hora_retorno' => $item->fecha_hora_retorno,
            'tiempo_pausado' => $item->fecha_hora_retorno ? CarbonInterval::seconds(Carbon::parse($item->fecha_hora_retorno)->diffInSeconds(Carbon::parse($item->fecha_hora_pausa)))->cascade()->forHumans() : null,
            'motivo' => $item->motivoPausaTicket->motivo,
            'responsable' => Empleado::extraerNombresApellidos($item->responsable),
        ]);

        return response()->json(compact('results'));
    }
}
