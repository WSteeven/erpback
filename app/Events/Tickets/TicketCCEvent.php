<?php

namespace App\Events\RecursosHumanos\Tickets;

use App\Models\Notificacion;
use App\Models\Ticket;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Src\Config\TiposNotificaciones;

class TicketCCEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $ticket;
    public Notificacion $notificacion;
    public int $destinatario;
    public int $emisor;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Ticket $ticket, int $emisor, int $destinatario)
    {
        $this->ticket = [
            'id' => $ticket->id,
            'estado' => $ticket->estado,
            'codigo' => $ticket->codigo,
            'responsable' => $ticket->responsable,
        ];

        $this->destinatario = $destinatario;
        $this->emisor = $emisor;

        $ruta = '/tickets-asignados';
        $mensaje = 'Se lo ha etiquetado en el ticket ' . $this->ticket['codigo'];

        $this->notificacion = Notificacion::crearNotificacion($mensaje, $ruta, TiposNotificaciones::TICKET, $emisor, $destinatario, $ticket, true);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        $canal = 'tickets-tracker-' . $this->destinatario;
        return new Channel($canal);
    }

    public function broadcastAs()
    {
        return 'ticket-event';
    }
}
