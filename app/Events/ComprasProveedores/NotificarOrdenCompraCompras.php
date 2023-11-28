<?php

namespace App\Events\ComprasProveedores;

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

class NotificarOrdenCompraCompras implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public OrdenCompra $orden;
    public Notificacion $notificacion;
    public string $url = '/ordenes-compras';
    public $canalId;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($orden, $canalId)
    {
        $this->orden = $orden;
        $this->canalId = $canalId;

        $this->notificacion = Notificacion::crearNotificacion($this->obtenerMensaje(), $this->url, TiposNotificaciones::ORDEN_COMPRA, auth()->user()->empleado->id, null, $orden, true);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('notificar-ordenes-compras-aprobadas-'.$this->canalId);
    }
    public function broadcastAs(){
        return 'notificar-orden-compra-event';
    }

    public function obtenerMensaje(){
        return  $this->orden->autorizador->nombres.' '.$this->orden->autorizador->apellidos.' ha aprobado una Orden de Compra N°'.$this->orden->codigo.' solicitada por '.$this->orden->solicitante->nombres.' '.$this->orden->solicitante->apellidos.'. Por favor establece precios y proveedor para que la orden de compra pueda ser impresa';
    }
}
