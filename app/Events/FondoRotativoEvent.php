<?php

namespace App\Events;

use App\Models\FondosRotativos\Gasto\Gasto;
use App\Models\Notificacion;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Src\Config\TiposNotificaciones;

class FondoRotativoEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Gasto $gasto;
    public Notificacion $notificacion;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($gasto)
    {
        $ruta = env('SPA_URL', 'http://localhost:8080').'/autorizar-gasto';
        $this->gasto = $gasto;
        $this->notificacion = Notificacion::crearNotificacion('Tienes un gasto por aprobar',$ruta, TiposNotificaciones::AUTORIZACION_GASTO, $this->gasto->id_usuario, $this->gasto->aut_especial);
    }


    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        //return new PrivateChannel('channel-name');
        return new Channel('fondo-rotativo-'. $this->gasto->aut_especial);
    }


    public function broadcastAs()
    {
        return 'fondo-rotativo-event';
    }
}
