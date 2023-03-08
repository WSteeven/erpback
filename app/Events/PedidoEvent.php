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

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(string $mensaje, $pedido)
    {
        $this->mensaje = $mensaje;
        $this->pedido= $pedido;

        // $this->notificacion = $this->crearNotificacion('Tienes un pedido por aprobar', $this->pedido->solicitante_id, $this->pedido->per_autoriza_id);

        /* Creating a notification with the message, the originator and the recipient */
        $mensaje = 'Pedido N°'.$this->pedido->id.' '.$this->pedido->solicitante->nombres.' '.$this->pedido->solicitante->apellidos. ' ha realizado un pedido en la sucursal '.$this->pedido->sucursal->lugar.' y está '.$this->pedido->autorizacion->nombre.' de autorización';
        $this->notificacion = $this->crearNotificacion($mensaje, $this->pedido->solicitante_id, $this->pedido->per_autoriza_id);
    }


    /**
     * It creates a notification with the message, the originator and the recipient
     *
     * @param mensaje The message you want to send.
     * @param originador The user who sent the message
     * @param destinatario The user who will receive the notification.
     */
    public static function crearNotificacion($mensaje, $originador, $destinatario){
        $notificacion = Notificacion::create([
            'mensaje'=>$mensaje,
            'link'=>env('SPA_URL', 'http://localhost:8080').'/pedidos',
            'per_originador_id'=>$originador,
            'per_destinatario_id'=>$destinatario,
            'tipo_notificacion'=>TiposNotificaciones::PEDIDO,
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
        return new Channel('pedidos-tracker-'.$this->pedido->per_autoriza_id);
    }

    public function broadcastAs(){
        return 'pedido-event';
    }
}
