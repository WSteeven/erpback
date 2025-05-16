<?php

namespace App\Events\Bodega;

use App\Models\EstadoTransaccion;
use App\Models\Notificacion;
use App\Models\Pedido;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Src\Config\EstadosTransacciones;
use Src\Config\TiposNotificaciones;

class NotificarPedidoParcial implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int $cantidad;
    public Pedido $pedido;
    public Notificacion $notificacion;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($pedido, $cantidad)
    {
        $ruta = '/pedidos';
        $this->cantidad = $cantidad;
        $this->pedido = $pedido;

        $this->notificacion = Notificacion::crearNotificacion($this->obtenerMensaje(), $ruta, TiposNotificaciones::PEDIDO, null, null, $pedido, true);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('pedidos-parciales-BODEGA');
    }
    public function broadcastAs(){
        return 'pedido-event';
    }

    public function obtenerMensaje(){
        return 'Estimado/a bodeguero, tienes '.$this->cantidad.' de pedidos con estado '. EstadoTransaccion::PARCIAL.' . Por favor completa el despacho de dichos pedidos';
    }
}
