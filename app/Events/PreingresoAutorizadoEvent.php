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
use Src\Config\Autorizaciones;
use Src\Config\TiposNotificaciones;

class PreingresoAutorizadoEvent implements ShouldBroadcast
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
    public function __construct($preingreso)
    {
        $this->preingreso = $preingreso;

        $this->notificacion = Notificacion::crearNotificacion($this->obtenerMensaje(), $this->url, TiposNotificaciones::PREINGRESO, $this->preingreso->autorizador_id, $this->preingreso->responsable_id, $this->preingreso, false);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('preingresos-actualizados-tracker-' . $this->preingreso->responsable_id);
    }

    public function broadcastAs()
    {
        return 'preingreso-event';
    }

    public function obtenerMensaje()
    {
        if ($this->preingreso->autorizacion_id == Autorizaciones::CANCELADO)
            return $this->preingreso->autorizador->nombres . ' ' . $this->preingreso->autorizador->apellidos . ' ha cancelado tu preingreso de materiales';
        else{
            if(is_null($this->preingreso->tarea_id)) return $this->preingreso->autorizador->nombres . ' ' . $this->preingreso->autorizador->apellidos . ' ha aprobado tu preingreso de materiales, dichos materiales se cargarán a tu stock personal';
            else return $this->preingreso->autorizador->nombres . ' ' . $this->preingreso->autorizador->apellidos . ' ha aprobado tu preingreso de materiales, dichos materiales se cargarán a tu stock de tarea '.$this->preingreso->tarea->codigo_tarea;
        } 
    }
}
