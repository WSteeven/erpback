<?php

namespace App\Jobs;

use App\Events\ActualizarNotificacionesEvent;

use App\Events\Tickets\TicketEvent;
use App\Models\ActividadRealizadaSeguimientoTicket;
use App\Models\MotivoPausaTicket;
use App\Models\Ticket;
use Carbon\Carbon;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PausarTicketsFinJornadaJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            // $condicion = Ticket::where('estado', Ticket::EJECUTANDO);
            $ticketsEjecutandose =  Ticket::where('estado', Ticket::EJECUTANDO)->get();

            foreach ($ticketsEjecutandose as $ticket) {
                $ticket->update(['estado' => Ticket::PAUSADO]);
                $ticket->pausasTicket()->create([
                    'fecha_hora_pausa' => Carbon::now(),
                    'motivo_pausa_ticket_id' => Ticket::PAUSA_AUTOMATICA_SISTEMA,
                    'responsable_id' => $ticket->responsable_id,
                ]);

                ActividadRealizadaSeguimientoTicket::create([
                    'ticket_id' => $ticket->id,
                    'fecha_hora' => Carbon::now(),
                    'observacion' => 'TICKET PAUSADO',
                    'actividad_realizada' => 'Ticket PAUSADO por el motivo: ' . MotivoPausaTicket::find(Ticket::PAUSA_AUTOMATICA_SISTEMA)->motivo,
                    'responsable_id' => $ticket->responsable_id,
                ]);

                $ticketAux = new Ticket();
                $ticket->refresh();
                $ticketAux->id = $ticket->id;
                $ticketAux->estado = $ticket->estado;

                event(new TicketEvent($ticketAux, $ticket->solicitante_id, $ticket->responsable_id, true));
                event(new TicketEvent($ticketAux, $ticket->responsable_id, $ticket->solicitante_id, true));
                event(new ActualizarNotificacionesEvent());
            }

            // Log::channel('testing')->info('Log', ['JOB5', 'Dentro del job tickets pausados']);
        } catch (Exception $e) {
            Log::channel('testing')->info('Log', ['error', $e->getMessage(), $e->getLine()]);
        }
    }
}
