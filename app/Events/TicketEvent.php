<?php

namespace App\Events;

use App\Models\Empleado;
use App\Models\MotivoPausaTicket;
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
    public function __construct(Ticket $ticket, int $emisor, int $destinatario, bool $sistema = false)
    {
        $this->ticket = $ticket;
        $this->destinatario = $destinatario;
        $this->emisor = $emisor;

        $ruta = '/tickets-asignados';
        $mensaje = $sistema ? $this->generarMensajeSistema() : $this->obtenerMensaje();

        $ticket->notificaciones()->update(['leida' => 1]);

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
                return Empleado::extraerNombresApellidos(Empleado::find($this->emisor)) . ' le ha ASIGNADO el ticket ' . $this->ticket->codigo . ' con asunto: ' . $this->ticket->asunto;
            case Ticket::REASIGNADO:
                return Empleado::extraerNombresApellidos(Empleado::find($this->emisor)) . ' le ha REASIGNADO el ticket ' . $this->ticket->codigo . ' con asunto: ' . $this->ticket->asunto;
            case Ticket::EJECUTANDO:
                return Empleado::extraerNombresApellidos($this->ticket->responsable) . ' ha comenzado a EJECUTAR el ticket ' . $this->ticket->codigo . ' con asunto: '  . $this->ticket->asunto;
            case Ticket::PAUSADO:
                $motivo = $this->ticket->pausasTicket()->orderBy('fecha_hora_pausa', 'DESC')->first()->motivoPausaTicket()->orderBy('created_at', 'DESC')->first()->motivo;
                return Empleado::extraerNombresApellidos($this->ticket->responsable) . ' ha PAUSADO el ticket ' . $this->ticket->codigo . ' con asunto: ' . $this->ticket->asunto . ', por el motivo: ' . $motivo;
            case Ticket::RECHAZADO:
                $motivo = $this->ticket->ticketsRechazados()->orderBy('fecha_hora', 'DESC')->first()->motivo;
                return Empleado::extraerNombresApellidos(Empleado::find($this->emisor)) . ' ha RECHAZADO el ticket ' . $this->ticket->codigo . ' con asunto: ' . $this->ticket->asunto . ', por el motivo: ' . $motivo;
            case Ticket::CANCELADO:
                return Empleado::extraerNombresApellidos(Empleado::find($this->emisor)) . ' le ha CANCELADO el ticket ' . $this->ticket->codigo . ' con asunto: ' . $this->ticket->asunto . ', por el motivo: ' . $this->ticket->motivoCanceladoTicket?->motivo;
            case Ticket::FINALIZADO_SOLUCIONADO || Ticket::FINALIZADO_SOLUCIONADO:
                return Empleado::extraerNombresApellidos($this->ticket->responsable) . ' ha FINALIZADO el ticket ' . $this->ticket->codigo . ' con asunto: ' . $this->ticket->asunto;
        }
    }

    private function generarMensajeSistema()
    {
        switch ($this->ticket->estado) {
            case Ticket::PAUSADO:
                $motivo = MotivoPausaTicket::find(Ticket::PAUSA_AUTOMATICA_SISTEMA)->motivo;
                return 'Ticket ' . $this->ticket->codigo . ' con asunto: ' . $this->ticket->asunto . ', ha sido PAUSADO. Motivo: ' . $motivo;
        }
    }
}
