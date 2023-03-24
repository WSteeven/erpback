<?php

namespace App\Events;

use App\Models\Notificacion;
use App\Models\Subtarea;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Src\Config\Endpoints;
use Src\Config\TiposNotificaciones;

class SubtareaEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $mensaje;
    public Subtarea $subtarea;
    public Notificacion $notificacion;
    public int $destinatario;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(string $mensaje, Subtarea $subtarea, $destinatario)
    {
        $this->mensaje = $mensaje;
        $this->subtarea = $subtarea;
        $this->destinatario = $destinatario;
        $this->notificacion = $this->crearNotificacion($mensaje, $subtarea->id, $destinatario);
    }

    public static function crearNotificacion($mensaje, $originador, $destinatario)
    {
        $notificacion = Notificacion::create([
            'mensaje' => $mensaje,
            'link' => 'subtareas',
            'per_originador_id' => $originador,
            'per_destinatario_id' => $destinatario,
            'tipo_notificacion' => TiposNotificaciones::SUBTAREA,
        ]);
        return $notificacion;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        // return new PrivateChannel('channel-name');
        return new Channel('subtareas-tracker');
    }

    /*public function broadcastWith()
    {
        $extra = [
            'campo1' => 'Mensaje numero #1',
            'campo2' => 'Mensaje numero #2',
        ];

        return $extra;
    }*/

    public function broadcastAs()
    {
        return 'subtarea-event';
    }
}
