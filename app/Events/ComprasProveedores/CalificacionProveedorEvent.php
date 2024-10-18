<?php

namespace App\Events\RecursosHumanos\ComprasProveedores;

use App\Models\Notificacion;
use App\Models\Proveedor;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Src\Config\TiposNotificaciones;

class CalificacionProveedorEvent implements ShouldBroadcast
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
    public function __construct($proveedor, $originador, $destinatario, $informativa)
    {
        $this->proveedor = $proveedor;

        $this->notificacion = Notificacion::crearNotificacion($this->obtenerMensaje(), $this->url, TiposNotificaciones::PROVEEDOR, $originador, $destinatario, $proveedor, $informativa);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        // Log::channel('testing')->info('Log', ['Notificacion en evento de calificación:', $this->notificacion]);
        return new Channel('proveedores-tracker-'.$this->notificacion->per_destinatario_id);
    }
    public function broadcastAs(){
        return 'proveedor-event';
    }

    public function obtenerMensaje(){
        return 'Se ha registrado un nuevo proveedor: '.$this->proveedor->empresa->razon_social.'. Sucursal: '.$this->proveedor->sucursal.'. Por favor califica el proveedor';
    }
}
