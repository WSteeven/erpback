<?php

namespace App\Events\Bodega;

use App\Models\Notificacion;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Src\Config\TiposNotificaciones;
use Throwable;

class PedidoAutorizadoEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $canalId;
    public Notificacion $notificacion;

    /**
     * Create a new event instance.
     *
     * @return void
     * @throws Throwable
     */

    public function __construct($mensaje, string $canal, $url, $pedido, $informativa)
    {
        $this->canalId = $canal;

        $this->notificacion = Notificacion::crearNotificacion($mensaje, $url, TiposNotificaciones::PEDIDO, null, null, $pedido, $informativa);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel
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
