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
use Illuminate\Support\Facades\Log;
use Src\Config\TiposNotificaciones;

class TicketEvent implements ShouldBroadcast
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
    public function __construct(Ticket $ticket, int $emisor, int $destinatario, bool $sistema = false)
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
        $mensaje = $sistema ? $this->generarMensajeSistema($ticket) : $this->obtenerMensaje($ticket);

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

    private function obtenerMensaje(Ticket $ticket)
    {
        switch ($ticket->estado) {
            case Ticket::ASIGNADO:
                return Empleado::extraerNombresApellidos(Empleado::find($this->emisor)) . ' le ha ASIGNADO el ticket ' . $ticket->codigo . ' con asunto: ' . $ticket->asunto;
            case Ticket::REASIGNADO:
                return Empleado::extraerNombresApellidos(Empleado::find($this->emisor)) . ' le ha REASIGNADO el ticket ' . $ticket->codigo . ' con asunto: ' . $ticket->asunto;
            case Ticket::EJECUTANDO:
                return Empleado::extraerNombresApellidos($ticket->responsable) . ' ha comenzado a EJECUTAR el ticket ' . $ticket->codigo . ' con asunto: '  . $ticket->asunto;
            case Ticket::PAUSADO:
                $motivo = $ticket->pausasTicket()->orderBy('fecha_hora_pausa', 'DESC')->first()->motivoPausaTicket()->orderBy('created_at', 'DESC')->first()->motivo;
                return Empleado::extraerNombresApellidos($ticket->responsable) . ' ha PAUSADO el ticket ' . $ticket->codigo . ' con asunto: ' . $ticket->asunto . ', por el motivo: ' . $motivo;
            case Ticket::RECHAZADO:
                $motivo = $ticket->ticketsRechazados()->orderBy('fecha_hora', 'DESC')->first()->motivo;
                return Empleado::extraerNombresApellidos(Empleado::find($this->emisor)) . ' ha RECHAZADO el ticket ' . $ticket->codigo . ' con asunto: ' . $ticket->asunto . ', por el motivo: ' . $motivo;
            case Ticket::CANCELADO:
                return Empleado::extraerNombresApellidos(Empleado::find($this->emisor)) . ' le ha CANCELADO el ticket ' . $ticket->codigo . ' con asunto: ' . $ticket->asunto . ', por el motivo: ' . $ticket->motivoCanceladoTicket?->motivo;
            case Ticket::FINALIZADO_SOLUCIONADO || Ticket::FINALIZADO_SOLUCIONADO:
                return Empleado::extraerNombresApellidos($ticket->responsable) . ' ha FINALIZADO el ticket ' . $ticket->codigo . ' con asunto: ' . $ticket->asunto;
        }
    }

    private function generarMensajeSistema(Ticket $ticket)
    {
        switch ($ticket->estado) {
            case Ticket::PAUSADO:
                $motivo = MotivoPausaTicket::find(Ticket::PAUSA_AUTOMATICA_SISTEMA)->motivo;
                return 'Ticket ' . $ticket->codigo . ' con asunto: ' . $ticket->asunto . ', ha sido PAUSADO. Motivo: ' . $motivo;
        }
    }
}
