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

    public string $mensaje;
    public Pedido $pedido;
    public Notificacion $notificacion;
    public int $destinatario;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(string $mensaje, $url, $pedido, $destinatario)
    {
        $this->mensaje = $mensaje;
        $this->pedido = $pedido;
        $this->destinatario = $destinatario;


        // $this->notificacion = $this->crearNotificacion('Tienes un pedido por aprobar', $this->pedido->solicitante_id, $this->pedido->per_autoriza_id);

        /* Creating a notification with the message, the originator and the recipient */
        $this->notificacion = Notificacion::crearNotificacion($this->mensaje, $url, TiposNotificaciones::PEDIDO,$this->pedido->solicitante_id, $this->destinatario);
    }


    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('pedidos-tracker-' . $this->pedido->per_autoriza_id);
    }

    public function broadcastAs()
    {
        return 'pedido-event';
    }
}
