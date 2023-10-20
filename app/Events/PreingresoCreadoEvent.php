<?php

namespace App\Events;

use App\Models\Notificacion;
use App\Models\PreingresoMaterial;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Src\Config\TiposNotificaciones;

class PreingresoCreadoEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $url = '/preingresos-materiales';
    public PreingresoMaterial $preingreso;
    public Notificacion $notificacion;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($preingreso, $destinatario)
    {
        $this->preingreso = $preingreso;

        $this->notificacion = Notificacion::crearNotificacion($this->obtenerMensaje(), $this->url, TiposNotificaciones::PREINGRESO, $this->preingreso->responsable_id, $destinatario, $this->preingreso, false);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('preingresos-tracker-' . $this->notificacion->per_destinatario_id);
    }
    public function broadcastAs()
    {
        return 'preingreso-event';
    }

    public function obtenerMensaje()
    {
        return $this->preingreso->responsable->nombres . ' ' . $this->preingreso->responsable->apellidos . ' ha realizado un preingreso de materiales para la cuadrilla' . $this->preingreso->cuadrilla . ' y está pendiente de aprobación. Por favor verifica y aprueba o cancela el preingreso';
    }
}
