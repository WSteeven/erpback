<?php

namespace App\Events\Bodega;

use App\Models\Notificacion;
use App\Models\Pedido;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Src\Config\TiposNotificaciones;
use Throwable;

class PedidoCreadoEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

        public int $autorizaId;
    public Notificacion $notificacion;

    /**
     * Create a new event instance.
     *
     * @return void
     * @throws Throwable
     */
    public function __construct(string $mensaje, $url, $pedido, $solicitante,  $destinatario, $informativa)
    {
        $this->autorizaId = $pedido->per_autoriza_id;

        // $this->notificacion = $this->crearNotificacion('Tienes un pedido por aprobar', $this->pedido->solicitante_id, $this->pedido->per_autoriza_id);

        /* Creating a notification with the message, the originator and the recipient */
        $this->notificacion = Notificacion::crearNotificacion($mensaje, $url, TiposNotificaciones::PEDIDO, $solicitante, $destinatario, $pedido, $informativa);
    }


    /**
     * Get the channels the event should broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [
            new Channel('pedidos-tracker-' . $this->autorizaId),
        ];
    }

    public function broadcastAs()
    {
        return 'pedido-event';
    }
    // 🔥 IMPORTANTE: controlamos el payload

    public function broadcastWith()
    {
        return [
            'notificacion' => [
                'mensaje' => $this->notificacion->mensaje,
                'link'    => $this->notificacion->url,
            ],
        ];
    }
}
