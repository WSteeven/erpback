<?php

namespace Src\App;

use App\Events\TicketEvent;
use App\Events\Tickets\TicketCCEvent;
use App\Models\Departamento;
use App\Models\Ticket;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class TicketService
{
    public function notificarTicketsAsignados($tickets)
    {
        foreach ($tickets as $ticket) {
            event(new TicketEvent($ticket, $ticket->solicitante_id, $ticket->responsable_id));
        }
    }

    public function notificarTicketsCC($tickets)
    {
        foreach ($tickets as $ticket) {
            $cc = json_decode($ticket->cc);
            foreach ($cc as $destinatario_id) {
                event(new TicketCCEvent($ticket, $ticket->solicitante_id, $destinatario_id));
            }
        }
    }

    public function crearMultiplesResponsablesMismoDepartamento($request)
    {
        $responsables = $request['responsable'];
        $destinatario = $request['destinatarios'][0];

        $tickets_creados = [];

        foreach ($responsables as $id) {
            $ticket = $this->crearTicketInterno($request, $destinatario, $id);
            array_push($tickets_creados, $ticket);
        }

        return $tickets_creados;
    }

    public function crearMultiplesDepartamentos($destinatarios, $request): array
    {
        $tickets_creados = [];

        foreach ($destinatarios as $destinatario) {
            $ticket = $this->crearTicket($request, $destinatario);
            array_push($tickets_creados, $ticket);
        }

        return $tickets_creados;
    }

    // Request: Destinatario['tipo_ticket_id', 'departamento_id']
    public function crearTicket($request, $destinatario)
    {
        $datos = $request->validated();
        $datos['codigo'] = 'TCKT-' . (Ticket::count() == 0 ? 1 : Ticket::latest('id')->first()->id + 1);
        $datos['responsable_id'] = $request['ticket_para_mi'] ? $request['responsable_id'] : Departamento::find($destinatario['departamento_id'])->responsable_id;  // $destinatario->responsable_id;
        $datos['solicitante_id'] = Auth::user()->empleado->id;
        $datos['tipo_ticket_id'] = $destinatario['tipo_ticket_id'];
        $datos['departamento_responsable_id'] = $destinatario['departamento_id'];
        $datos['ticket_para_mi'] = $request->safe()->only(['ticket_para_mi'])['ticket_para_mi'];
        $datos['cc'] = json_encode($request['cc']);

        Log::channel('testing')->info('Log', ['Datos', $datos]);

        // Calcular estados
        $datos['estado'] = Ticket::ASIGNADO;

        return Ticket::create($datos);
    }

    public function crearTicketInterno($request, $destinatario, $responsable_id)
    {
        $datos = $request->validated();
        $datos['codigo'] = 'TCKT-' . (Ticket::count() == 0 ? 1 : Ticket::latest('id')->first()->id + 1);
        $datos['responsable_id'] = $responsable_id;
        $datos['solicitante_id'] = Auth::user()->empleado->id;
        $datos['tipo_ticket_id'] = $destinatario['tipo_ticket_id']; // $request->safe()->only(['tipo_ticket'])['tipo_ticket'];
        $datos['departamento_responsable_id'] = $destinatario['departamento_id'];
        $datos['ticket_para_mi'] = $request->safe()->only(['ticket_para_mi'])['ticket_para_mi'];
        $datos['cc'] = json_encode($request['cc']);

        // Calcular estados
        $datos['estado'] = Ticket::ASIGNADO;
        return Ticket::create($datos);
    }

    /* public function crearTicket($request, int $responsable_id, int $departamento_responsable_id)
    {
        $datos = $request->validated();
        $datos['codigo'] = 'TCKT-' . (Ticket::count() == 0 ? 1 : Ticket::latest('id')->first()->id + 1);
        // $datos['responsable_id'] = $request->safe()->only(['responsable'])['responsable'];
        $datos['responsable_id'] = $responsable_id;
        $datos['solicitante_id'] = Auth::user()->empleado->id;
        $datos['tipo_ticket_id'] = $request->safe()->only(['tipo_ticket'])['tipo_ticket'];
        // $datos['departamento_responsable_id'] = $request->safe()->only(['departamento_responsable'])['departamento_responsable'];
        $datos['departamento_responsable_id'] = $departamento_responsable_id;
        $datos['ticket_para_mi'] = $request->safe()->only(['ticket_para_mi'])['ticket_para_mi'];

        // Calcular estados
        $datos['estado'] = Ticket::ASIGNADO;

        return Ticket::create($datos);
    }*/
    // public function __construct() { }

    public function puedeFinalizar(Ticket $ticket)
    {
        // Log::channel('testing')->info('Log', ['listado', $ticket->actividadesRealizadasSeguimientoTicket->count()]);
        if ($ticket->actividadesRealizadasSeguimientoTicket->count() < 1) {
            throw ValidationException::withMessages([
                'pocas_actividades' => ['Ingrese al menos una actividad en el seguimiento!'],
            ]);
        }

        if ($ticket->actividadesRealizadasSeguimientoTicket()->whereNotNull('fotografia')->get()->isEmpty() && $ticket->archivosSeguimientos->count() == 0) {
            throw ValidationException::withMessages([
                'falta_fotografia_archivo' => ['Ingrese al menos una actividad con fotografía o un archivo en el seguimiento!'],
            ]);
        }
    }

    public function puedePausar(Ticket $ticket)
    {
        if ($ticket->actividadesRealizadasSeguimientoTicket->count() < 2) {
            throw ValidationException::withMessages([
                'pocas_actividades' => ['Ingrese al menos una actividad en el seguimiento!'],
            ]);
        }
    }
}
