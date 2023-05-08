<?php

namespace App\Events;

use App\Models\Notificacion;
use App\Models\Pedido;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Src\Config\TiposNotificaciones;

class PedidoCreadoEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Pedido $pedido;
    public Notificacion $notificacion;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(string $mensaje, $url, $pedido, $solicitante,  $destinatario, $informativa)
    {
        $this->pedido = $pedido;


        // $this->notificacion = $this->crearNotificacion('Tienes un pedido por aprobar', $this->pedido->solicitante_id, $this->pedido->per_autoriza_id);

        /* Creating a notification with the message, the originator and the recipient */
        $this->notificacion = Notificacion::crearNotificacion($mensaje, $url, TiposNotificaciones::PEDIDO, $solicitante, $destinatario, $pedido, $informativa);
    }


    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return [
            new Channel('pedidos-tracker-' . $this->pedido->per_autoriza_id),
        ];
    }

    public function broadcastAs()
    {
        return 'pedido-event';
    }
}
