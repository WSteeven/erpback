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

class OrdenCompraActualizadaEvent implements ShouldBroadcast
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

        $this->notificacion = Notificacion::crearNotificacion($this->obtenerMensaje(), $this->url, TiposNotificaciones::ORDEN_COMPRA, $orden->autorizador_id, $orden->solicitante_id, $orden, $informativa);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('ordenes-actualizadas-tracker-'.$this->orden->solicitante_id);
    }
    public function broadcastAs(){
        return 'orden-event';
    }

    public function obtenerMensaje()
    {
        if ($this->orden->autorizacion->nombre == Autorizacion::APROBADO) {
            return $this->orden->autorizador->nombres . ' ' . $this->orden->autorizador->apellidos . ' ha aprobado tu orden de compra N° '.$this->orden->id;
        }
        return $this->orden->autorizador->nombres . ' ' . $this->orden->autorizador->apellidos . ' ha modificado la orden de compra que generaste. Orden N° '.$this->orden->id;
    }
}
