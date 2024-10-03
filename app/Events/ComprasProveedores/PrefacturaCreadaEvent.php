<?php

namespace App\Events\RecursosHumanos\ComprasProveedores;

use App\Models\Autorizacion;
use App\Models\ComprasProveedores\Prefactura;
use App\Models\Notificacion;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Src\Config\TiposNotificaciones;

class PrefacturaCreadaEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Prefactura $prefactura;
    public Notificacion $notificacion;
    public string $url='/prefacturas';

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($prefactura, $informativa)
    {
        $this->prefactura = $prefactura;

        $this->notificacion = Notificacion::crearNotificacion($this->obtenerMensaje(), $this->url, TiposNotificaciones::PREFACTURA, $prefactura->solicitante_id, $prefactura->autorizador_id, $prefactura, $informativa );
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('prefacturas-tracker-'.$this->prefactura->autorizador_id);
    }
    public function broadcastAs(){
        return 'prefactura-event';
    }

    public function obtenerMensaje(){
        return 'Se ha creado una prefactura N° ' . $this->prefactura->id . ' cuya autorización es ' . Autorizacion::PENDIENTE . '. Por favor verifica y autoriza o anula la prefactura';
    }
}
