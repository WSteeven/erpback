<?php

namespace App\Http\Controllers;

use App\Http\Requests\TicketRequest;
use App\Http\Resources\TicketResource;
use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        $modelo = Ticket::create($datos);

        $modelo = new TicketResource($modelo->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');

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

        $ticket->departamento_responsable_id = $request['departamento_responsable'];
        $ticket->responsable_id = $request['responsable'];
        $ticket->save();

        $modelo = new TicketResource($ticket->refresh());
        $mensaje = 'Responsable cambiado exitosamente!';
        return response()->json(compact('modelo', 'mensaje'));
    }
}
