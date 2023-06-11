<?php

namespace App\Http\Controllers;

use App\Events\TicketEvent;
use App\Http\Requests\TicketRequest;
use App\Http\Resources\TicketResource;
use App\Models\ActividadRealizadaSeguimientoTicket;
use App\Models\Empleado;
use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Src\Shared\Utils;

class TicketController extends Controller
{
    private $entidad = 'Ticket';

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
            event(new TicketEvent($ticket, $idResponsableAnterior, $ticket->responsable_id));
            ActividadRealizadaSeguimientoTicket::create([
                'ticket_id' => $ticket->id,
                'fecha_hora' => Carbon::now(),
                'observacion' => '« TRANSFERENCIA »',
                'actividad_realizada' => '« '. Empleado::extraerNombresApellidos(Empleado::find($idResponsableAnterior)) . '» le ha transferido el ticket ' . $ticket->codigo . ' a «' . Empleado::extraerNombresApellidos($ticket->responsable) . '».',
            ]);

            event(new TicketEvent($ticket, $idResponsableAnterior, $modelo->responsable_id));
        } else {
            event(new TicketEvent($ticket, $modelo->solicitante_id, $modelo->responsable_id));
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
        ]);

        $modelo = new TicketResource($ticket->refresh());
        $mensaje = 'Ticket pausado exitosamente!';

        event(new TicketEvent($ticket, $modelo->responsable_id, $modelo->solicitante_id));
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

        //event(new SubtareaEvent($subtarea, User::ROL_COORDINADOR));
        return response()->json(compact('modelo', 'mensaje'));
    }

    public function finalizar(Ticket $ticket)
    {
        $ticket->estado = Ticket::FINALIZADO_SOLUCIONADO;
        $ticket->fecha_hora_finalizado = Carbon::now();
        $ticket->save();

        $modelo = new TicketResource($ticket->refresh());
        $mensaje = 'Ticket finalizado exitosamente!';

        event(new TicketEvent($ticket, $modelo->responsable_id, $modelo->solicitante_id));

        return response()->json(compact('modelo', 'mensaje'));
    }

    public function rechazar(Ticket $ticket)
    {
        $ticket->estado = Ticket::RECHAZADO;
        $ticket->responsable_id = NULL;
        $ticket->departamento_responsable_id = NULL;
        $ticket->save();

        $modelo = new TicketResource($ticket->refresh());
        $mensaje = 'Ticket rechazado exitosamente!';

        return response()->json(compact('modelo', 'mensaje'));
    }
}
