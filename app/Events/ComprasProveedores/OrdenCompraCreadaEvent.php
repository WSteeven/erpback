<?php

namespace App\Events\ComprasProveedores;

use App\Models\Autorizacion;
use App\Models\ComprasProveedores\OrdenCompra;
use App\Models\Notificacion;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Src\Config\TiposNotificaciones;

class OrdenCompraCreadaEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public OrdenCompra $orden;
    public Notificacion $notificacion;
    public string $url = '/ordenes-compras';

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($orden, $informativa)
    {
        $this->orden = $orden;

        $this->notificacion = Notificacion::crearNotificacion($this->obtenerMensaje(), $this->url, TiposNotificaciones::ORDEN_COMPRA, $orden->solicitante_id, $orden->destinatario_id, $orden, $informativa);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('ordenes-tracker-' . $this->orden->autorizador_id);
    }
    public function broadcastAs()
    {
        return 'orden-event';
    }


    /**
     * La función devuelve un mensaje con el ID del pedido y el estado de autorización.
     * 
     * @return string una cadena que incluye el ID del pedido y el estado de la autorización. El mensaje le
     * pide al destinatario que verifique y autorice o cancele el pedido.
     */
    public function obtenerMensaje()
    {
        return 'Se ha creado una orden de compra N° ' . $this->orden->id . ' cuya autorización es ' . Autorizacion::PENDIENTE . '. Por favor verifica y autoriza o anula la orden de compra';
    }
}
