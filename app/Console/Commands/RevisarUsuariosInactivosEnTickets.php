<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Ticket;
use App\Models\ActividadRealizadaSeguimientoTicket;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class RevisarUsuariosInactivosEnTickets extends Command
{
    protected $signature = 'tickets:revisar-inactivos {jefe_id?}';

    protected $description = 'Revisa los tickets y reasigna automáticamente si el responsable está inactivo';

    public function handle()
    {
        $jefeId = $this->argument('jefe_id');

        $ticketsQuery = Ticket::whereNotIn('estado', [
            Ticket::CANCELADO,
            Ticket::FINALIZADO_SOLUCIONADO,
            Ticket::FINALIZADO_SIN_SOLUCION
        ])->with(['responsable.jefe']);

        // Si se pasa un jefe, filtra solo sus empleados
        if ($jefeId) {
            $ticketsQuery->whereHas('responsable', function ($q) use ($jefeId) {
                $q->where('jefe_id', $jefeId);
            });
        }

        $ticketsQuery->chunk(100, function ($tickets) {
            foreach ($tickets as $ticket) {
                $this->procesarTicket($ticket);
            }
        });

        $this->info("Revisión completada.");
        Log::info("Revisión de tickets ejecutada: " . now());
    }


    private function procesarTicket(Ticket $ticket)
    {
        $responsable = $ticket->responsable;

        //  Solo actuamos si el responsable existe y está INACTIVO (estado = 0)
        if ($responsable && $responsable->estado == 0) {
            $nuevoResponsable = $responsable->jefe?->id;

            if ($nuevoResponsable) {
                $ticket->responsable_id = $nuevoResponsable;
                $ticket->estado = Ticket::REASIGNADO; // SE LO REASIGNAMOS
                $ticket->save();

                ActividadRealizadaSeguimientoTicket::create([
                    'ticket_id' => $ticket->id,
                    'fecha_hora' => now(),
                    'observacion' => 'TICKET REASIGNADO',
                    'actividad_realizada' => "El responsable {$responsable->nombres} {$responsable->apellidos} está inactivo. Ticket reasignado automáticamente al jefe inmediato {$responsable->jefe->nombres} {$responsable->jefe->apellidos}.",
                    'responsable_id' => $nuevoResponsable,
                ]);

                $this->info(" Ticket {$ticket->id} reasignado automáticamente.");
            } else {
                $this->warn(" Ticket {$ticket->id} no pudo reasignarse (sin jefe definido).");
            }
        } else {
            // No se toca si el responsable sigue activo
            $this->info(" Ticket {$ticket->id} sin cambios (responsable activo).");
        }
    }
}
