<?php

namespace Src\App;

use App\Models\Ticket;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class TicketService
{
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
}
