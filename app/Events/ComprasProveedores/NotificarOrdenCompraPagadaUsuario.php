<?php

namespace App\Events\RecursosHumanos\ComprasProveedores;

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

class NotificarOrdenCompraPagadaUsuario implements ShouldBroadcast
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
    public function __construct($orden)
    {
        $this->orden = $orden;

        $this->notificacion = Notificacion::crearNotificacion($this->obtenerMensaje(), $this->url, TiposNotificaciones::ORDEN_COMPRA, auth()->user()->empleado->id, $this->orden->solicitante_id, $orden, true);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('notificar-ordenes-pagadas-' . $this->orden->solicitante_id);
    }
    public function broadcastAs()
    {
        return 'notificar-orden-pagada';
    }
    public function obtenerMensaje()
    {
        return   'La Orden de Compra N°' . $this->orden->codigo . ' solicitada por ' . $this->orden->solicitante->nombres . ' ' . $this->orden->solicitante->apellidos . ' ha sido pagada al proveedor';
    }
}
