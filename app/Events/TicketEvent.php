<?php

namespace App\Events;

use App\Models\Empleado;
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

class TicketEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Ticket $ticket;
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
        $this->ticket = $ticket;
        $this->destinatario = $destinatario;
        $this->emisor = $emisor;

        $ruta = '/tickets-asignados';
        $mensaje = $this->obtenerMensaje();

        $this->notificacion = Notificacion::crearNotificacion($mensaje, $ruta, TiposNotificaciones::TICKET, $emisor, $destinatario, $ticket, true);
    }

    public function broadcastOn()
    {
        $canal = 'tickets-tracker-' . $this->destinatario;
        return new Channel($canal);
    }

    public function broadcastAs()
    {
        return 'ticket-event';
    }

    private function obtenerMensaje()
    {
        switch ($this->ticket->estado) {
            case Ticket::ASIGNADO:
                return Empleado::extraerNombresApellidos(Empleado::find($this->emisor)) . ' le ha asignado el ticket ' . $this->ticket->codigo . '.';
            case Ticket::REASIGNADO:
                return Empleado::extraerNombresApellidos(Empleado::find($this->emisor)) . ' le ha transferido el ticket ' . $this->ticket->codigo . '.';
            case Ticket::EJECUTANDO:
                return Empleado::extraerNombresApellidos($this->ticket->responsable) . ' ha comenzado a EJECUTAR el ticket ' . $this->ticket->codigo . '.';
            case Ticket::PAUSADO:
                return Empleado::extraerNombresApellidos($this->ticket->responsable) . ' ha PAUSADO el ticket ' . $this->ticket->codigo . '.';
            case Ticket::RECHAZADO:
                return Empleado::extraerNombresApellidos($this->ticket->responsable) . ' ha RECHAZADO el ticket ' . $this->ticket->codigo . '.';
            case Ticket::FINALIZADO_SOLUCIONADO || Ticket::FINALIZADO_SOLUCIONADO:
                return Empleado::extraerNombresApellidos($this->ticket->responsable) . ' ha FINALIZADO el ticket ' . $this->ticket->codigo . '.';
        }
    }
}
