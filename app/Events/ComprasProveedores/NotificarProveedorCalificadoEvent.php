<?php

namespace App\Events\ComprasProveedores;

use App\Models\Notificacion;
use App\Models\Proveedor;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Src\Config\TiposNotificaciones;

class NotificarProveedorCalificadoEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public Proveedor $proveedor;
    public Notificacion $notificacion;
    public string $url = '/proveedores';
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($mensaje, $proveedor, $originador, $destinatario, $informativa)
    {
        $this->proveedor = $proveedor;

        $this->notificacion = Notificacion::crearNotificacion($mensaje, $this->url, TiposNotificaciones::PROVEEDOR, $originador, $destinatario, $proveedor, $informativa);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('proveedores-tracker-' . $this->notificacion->per_destinatario_id);
    }
    public function broadcastAs()
    {
        return 'proveedor-calificado-event';
    }
}
