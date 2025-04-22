<?php

namespace App\Http\Controllers;

use App\Events\ActualizarNotificacionesEvent;
use App\Events\Tickets\TicketEvent;
use App\Helpers\Filtros\FiltroSearchHelper;
use App\Http\Requests\TicketRequest;
use App\Http\Resources\TicketResource;
use App\Mail\Tickets\EnviarMailTicket;
use App\Models\ActividadRealizadaSeguimientoTicket;
use App\Models\Empleado;
use App\Models\MotivoPausaTicket;
use App\Models\Tareas\SolicitudAts;
use App\Models\Ticket;
use App\Models\TicketRechazado;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Src\App\TicketService;
use Src\Shared\Utils;
use Illuminate\Support\Facades\Mail;
use Src\Config\Constantes;

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
        // $campos = request('campos') ? explode(',', request('campos')) : '*';
        $search = request('search');
        $paginate = request('paginate');

        if (request('estado') === Ticket::ETIQUETADOS_A_MI) $query = Ticket::whereJsonContains('cc', intval(request('responsable_id')))->latest();
        else if (request('estado') === Ticket::RECURRENTE) $query = Ticket::ignoreRequest(['estado', 'paginate'])->filter()->where('is_recurring', true)->latest();
        else $query = Ticket::ignoreRequest(['campos', 'paginate'])->filter()->latest();

        $filtros = [
<<<<<<< Updated upstream
<<<<<<< Updated upstream
            ['clave' => 'estado', 'valor' => "'" . request('estado') . "'"],
        ];

        $filtros = FiltroSearchHelper::formatearFiltrosPorMotor($filtros);
        Log::channel('testing')->info('Log', ['Filtros: ', $filtros]);
        
        $results = buscarConAlgoliaFiltrado(Ticket::class, $query, 'id', $search, Constantes::PAGINATION_ITEMS_PER_PAGE, request('page'), !!$paginate, $filtros);
        Log::channel('testing')->info('Log', ['Results: ', $results]);
        return TicketResource::collection($results);
=======
            ['clave' => 'estado', 'valor' => request('estado')],
        ];

        $filtros = FiltroSearchHelper::formatearFiltrosPorMotor($filtros);

        $results = buscarConAlgoliaFiltrado(Ticket::class, $query, 'id', $search, Constantes::PAGINATION_ITEMS_PER_PAGE, request('page'), !!$paginate, $filtros);
        return $results = TicketResource::collection($results);
>>>>>>> Stashed changes
=======
            ['clave' => 'estado', 'valor' => request('estado')],
        ];

        $filtros = FiltroSearchHelper::formatearFiltrosPorMotor($filtros);

        $results = buscarConAlgoliaFiltrado(Ticket::class, $query, 'id', $search, Constantes::PAGINATION_ITEMS_PER_PAGE, request('page'), !!$paginate, $filtros);
        return $results = TicketResource::collection($results);
>>>>>>> Stashed changes
    }

    public function store(TicketRequest $request)
    {
        // Adaptacion de foreign keys
        $destinatarios = $request['destinatarios']; // array

        if ($request['ticket_interno']) {
            $tickets_creados = $this->servicio->crearMultiplesResponsablesMismoDepartamento($request);
        } else if ($request['para_sso']) {
            $ticket = $this->servicio->crearTicket($request, [
                'tipo_ticket_id' => Ticket::TIPO_TICKET_ATS,
                'departamento_id' => Ticket::SSO,
            ]);

            $tickets_creados = [$ticket];

            SolicitudAts::create([
                'ticket_id' => $ticket->id,
                'subtarea_id' => $request['subtarea_id'],
            ]);
        } else {
            $tickets_creados = $this->servicio->crearMultiplesDepartamentos($destinatarios, $request);
        }

        // $modelo = new TicketResource($ticket->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');

        // event(new TicketEvent($ticket, $modelo->solicitante_id, $modelo->responsable_id));
        $this->servicio->notificarTicketsAsignados($tickets_creados);
        $this->servicio->notificarTicketsCC($tickets_creados);
        event(new ActualizarNotificacionesEvent());

        $ids_tickets_creados = array_map(fn($ticket) => $ticket->id, $tickets_creados);
        $modelo = end($tickets_creados);
        $modelo = new TicketResource($modelo->refresh());

        return response()->json(compact('mensaje', 'modelo', 'ids_tickets_creados'));
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

        Mail::to($ticket->responsable->user->email)->send(new EnviarMailTicket($ticket));

        event(new TicketEvent($ticket->refresh(), $modelo->solicitante_id, $modelo->responsable_id));
        event(new ActualizarNotificacionesEvent());

        return response()->json(compact('modelo', 'mensaje'));
    }

    // Reasignar
    public function cambiarResponsable(Request $request, Ticket $ticket)
    {
        $request->validate([
            'departamento_responsable' => 'required|numeric|integer',
            'responsable' => 'required|numeric|integer',
            'cc' => 'nullable|array',
        ]);

        $idResponsableAnterior = $ticket->responsable_id;

        $ticket->departamento_responsable_id = $request['departamento_responsable'];
        $ticket->responsable_id = $request['responsable'];
        $ticket->estado = Ticket::REASIGNADO;
        $ticket['cc'] = json_encode($request['cc']);
        $ticket->save();

        $modelo = new TicketResource($ticket->refresh());
        $mensaje = 'Responsable cambiado exitosamente!';

        if (Auth::user()->empleado->id == $idResponsableAnterior) {
            // event(new TicketEvent($ticket, $idResponsableAnterior, $ticket->responsable_id));
            ActividadRealizadaSeguimientoTicket::create([
                'ticket_id' => $ticket->id,
                'fecha_hora' => Carbon::now(),
                'observacion' => 'TICKET REASIGNADO',
                'actividad_realizada' => Empleado::extraerNombresApellidos(Empleado::query()->find($idResponsableAnterior)) . ' le ha REASIGNADO el ticket a ' . Empleado::extraerNombresApellidos($ticket->responsable) . '.',
                'responsable_id' => $idResponsableAnterior,
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

        ActividadRealizadaSeguimientoTicket::create([
            'ticket_id' => $ticket->id,
            'fecha_hora' => Carbon::now(),
            'observacion' => 'TICKET EJECUTADO',
            'actividad_realizada' => Empleado::extraerNombresApellidos(Auth::user()->empleado) . ' ha EJECUTADO el ticket.',
            'responsable_id' => Auth::user()->empleado->id,
        ]);

        $modelo = new TicketResource($ticket->refresh());
        $mensaje = 'Ticket ejecutado exitosamente!';

        event(new TicketEvent($ticket, $modelo->responsable_id, $modelo->solicitante_id));
        event(new ActualizarNotificacionesEvent());

        return response()->json(compact('modelo', 'mensaje'));
    }

    public function pausar(Request $request, Ticket $ticket)
    {
        $this->servicio->puedePausar($ticket);

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
            'observacion' => 'TICKET PAUSADO',
            'actividad_realizada' => Empleado::extraerNombresApellidos($ticket->responsable) . ' ha pausado el ticket por el motivo: ' . MotivoPausaTicket::find($motivo_pausa_id)->motivo,
            'responsable_id' => Auth::user()->empleado->id,
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

        ActividadRealizadaSeguimientoTicket::create([
            'ticket_id' => $ticket->id,
            'fecha_hora' => Carbon::now(),
            'observacion' => 'TICKET EJECUTADO',
            'actividad_realizada' => Empleado::extraerNombresApellidos(Auth::user()->empleado) . ' ha REANUDADO el ticket.',
            'responsable_id' => Auth::user()->empleado->id,
        ]);

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

        ActividadRealizadaSeguimientoTicket::create([
            'ticket_id' => $ticket->id,
            'fecha_hora' => Carbon::now(),
            'observacion' => 'TICKET FINALIZADO',
            'actividad_realizada' => Empleado::extraerNombresApellidos(Auth::user()->empleado) . ' ha FINALIZADO el ticket.',
            'responsable_id' => Auth::id(),
        ]);

        $ticket->estado = Ticket::FINALIZADO_SOLUCIONADO;
        $ticket->fecha_hora_finalizado = Carbon::now();
        $ticket->save();

        $modelo = new TicketResource($ticket->refresh());
        $mensaje = 'Ticket finalizado exitosamente!';

        // Mail::to($ticket->solicitante->user->email)->send(new EnviarMailTicket($ticket->refresh()));

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
        // $ticket->responsable_id = NULL;
        // $ticket->departamento_responsable_id = NULL;
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

        Mail::to($ticket->solicitante->user->email)->send(new EnviarMailTicket($ticket));

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
        $results = $ticket->ticketsRechazados->map(fn($item) => [
            'fecha_hora' => $item->fecha_hora,
            'motivo' => $item->motivo,
            'responsable' => Empleado::extraerNombresApellidos($item->responsable),
        ]);

        return response()->json(compact('results'));
    }

    public function obtenerPausas(Ticket $ticket)
    {
        $results = $ticket->pausasTicket->map(fn($item) => [
            'fecha_hora_pausa' => $item->fecha_hora_pausa,
            'fecha_hora_retorno' => $item->fecha_hora_retorno,
            'tiempo_pausado' => $item->fecha_hora_retorno ? CarbonInterval::seconds(Carbon::parse($item->fecha_hora_retorno)->diffInSeconds(Carbon::parse($item->fecha_hora_pausa)))->cascade()->forHumans() : null,
            'motivo' => $item->motivoPausaTicket->motivo,
            'responsable' => Empleado::extraerNombresApellidos($item->responsable),
        ]);

        return response()->json(compact('results'));
    }

    public function auditoria($ticket_id)
    {
        $modelo = Ticket::find($ticket_id);
        $auditoria = $modelo->audits()->get(['user_id', 'new_values', 'created_at']);
        $auditoria = $auditoria->map(function ($item) {
            $empleado = User::find($item->user_id)?->empleado;
            if (!$empleado) return [];
            return [
                'responsable' => Empleado::extraerNombresApellidos($empleado),
                'estado' => array_key_exists('estado', $item->new_values) ? $item->new_values['estado'] : null,
                'created_at' => Carbon::parse($item->created_at)->format('Y-m-d H:i:s'),
                'departamento' => $empleado->departamento?->nombre,
                'foto' => $empleado->foto_url ? url($empleado->foto_url) : url('/storage/sinfoto.png'),
            ];
        });

        $results = array_values($auditoria->filter(fn($item) => $item['estado'] !== Ticket::ASIGNADO)->toArray());
        // Log::channel('testing')->info('Log', compact('results'));
        return response()->json(compact('results'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(TicketRequest $request, Ticket $ticket)
    {
        return DB::transaction(function () use ($request, $ticket) {
            $datos = $request->validated();
            $ticket->update($datos);
            $modelo = new TicketResource($ticket->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        });
    }

    // Actualizar el estado de recurrencia
    /* public function toggleRecurrence($id, Request $request)
    {
        $ticket = Ticket::where('id', $id)
            ->whereNull('parent_ticket_id')
            ->where('is_recurring', true)
            ->firstOrFail();

        $validated = $request->validate([
            'recurrence_active' => 'required|boolean'
        ]);

        $ticket->update([
            'recurrence_active' => $validated['recurrence_active']
        ]);

        return response()->json([
            'mensaje' => $ticket->recurrence_active ? 'Recurrencia reanudada' : 'Recurrencia pausada',
            'modelo' => $ticket
        ]);
    } */
}
