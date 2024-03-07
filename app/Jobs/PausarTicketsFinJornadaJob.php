<?php

namespace App\Jobs;

use App\Events\TicketEvent;
use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

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
        $ticketsEjecutandose = Ticket::where('estado', Ticket::EJECUTANDO)->get();
        $ticketsEjecutandose->update('estado', Ticket::PAUSADO);

        foreach ($ticketsEjecutandose as $ticket) {
            $ticket->pausasTicket()->create([
                'fecha_hora_pausa' => Carbon::now(),
                'motivo_pausa_ticket_id' => Ticket::FIN_DE_JORNADA,
                'responsable_id' => $ticket->responsable_id,
            ]);

            event(new TicketEvent($ticket->refresh(), $ticket->solicitante_id, $ticket->responsable_id, true));
        }
    }
}
