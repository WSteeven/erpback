<?php

namespace Src\App;

use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class TicketService
{
    public function crearTicket($request, int $responsable_id, int $departamento_responsable_id)
    {
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
    }
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
