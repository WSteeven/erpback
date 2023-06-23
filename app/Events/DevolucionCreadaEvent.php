<?php

namespace App\Events;

use App\Models\Devolucion;
use App\Models\Notificacion;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Src\Config\TiposNotificaciones;

class DevolucionCreadaEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Devolucion $devolucion;
    public Notificacion $notificacion;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(string $mensaje, $url, $devolucion, $solicitante,  $destinatario, $informativa)
    {
        $this->devolucion = $devolucion;
        
        /* Creating a notification with the message, the originator and the recipient */
        $this->notificacion = Notificacion::crearNotificacion($mensaje, $url, TiposNotificaciones::DEVOLUCION, $solicitante, $destinatario, $devolucion, $informativa);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        
        return new Channel('devoluciones-tracker-'. $this->devolucion->per_autoriza_id);
    }

    public function broadcastAs(){
        return 'devolucion-event';
    }
}
