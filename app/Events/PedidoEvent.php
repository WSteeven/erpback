<?php

namespace App\Events;

use App\Models\Notificacion;
use App\Models\Pedido;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Src\Config\TiposNotificaciones;

class PedidoEvent implements ShouldBroadcast
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
        $this->notificacion = $this->crearNotificacion($this->mensaje, $url, $this->pedido->solicitante_id, $this->destinatario);
    }


    /**
     * It creates a notification with the message, the originator and the recipient
     *
     * @param mensaje The message you want to send.
     * @param originador The user who sent the message
     * @param destinatario The user who will receive the notification.
     */
    public static function crearNotificacion($mensaje, $url, $originador, $destinatario)
    {
        // '/pedidos'
        $notificacion = Notificacion::create([
            'mensaje' => $mensaje,
            'link' => $url,
            'per_originador_id' => $originador,
            'per_destinatario_id' => $destinatario,
            'tipo_notificacion' => TiposNotificaciones::PEDIDO,
        ]);
        return $notificacion;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        // return new PrivateChannel('pedidos-tracker');
        Log::channel('testing')->info('Log', ['Estamos en el broadcastOn de pedidos', $this->pedido]);
        return new Channel('pedidos-tracker-' . $this->pedido->per_autoriza_id);
    }

    public function broadcastAs()
    {
        return 'pedido-event';
    }
}
