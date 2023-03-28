<?php

namespace App\Events;

use App\Models\Notificacion;
use App\Models\Pedido;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Src\Config\TiposNotificaciones;

class PedidoAutorizadoEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $canalId;
    public Notificacion $notificacion;
    /**
     * Create a new event instance.
     *
     * @return void
     */

    public function __construct($mensaje, $canal, $url)
    {
        $this->canalId = $canal;

        $this->notificacion = Notificacion::crearNotificacion($mensaje, $url, TiposNotificaciones::PEDIDO, null, null);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('pedidos-aprobados-' . $this->canalId);
    }
    public function broadcastAs()
    {
        return 'pedido-event';
    }
}
