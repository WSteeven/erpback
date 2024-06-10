<?php

namespace App\Events;

use App\Models\Departamento;
use App\Models\Empleado;
use App\Models\Notificacion;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Src\Config\TiposNotificaciones;

class VacacionNotificacionEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Empleado $empleado;
    public Notificacion $notificacion;
    public  $jefeInmediato = 0;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($empleado)
    {
        $ruta = '/empleado';
        $this->empleado = $empleado;
        $informativa = true;
        $this->jefeInmediato = Departamento::where('id', 7)->first()->responsable_id;
        $destinatario = $empleado->id ;
        $remitente =$this->jefeInmediato ;
        $mensaje = $this->empleado->nombres.''. $this->empleado->apellidos. ' Esta proximo a tener vacaciones';
        $this->notificacion = Notificacion::crearNotificacion($mensaje, $ruta, TiposNotificaciones::VACACION, $destinatario, $remitente, $empleado, $informativa);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        $nombre_chanel ='notificacion-vacacion-' . $this->jefeInmediato;
        return new Channel($nombre_chanel);
    }


    public function broadcastAs()
    {
        return 'notificacion-vacacion-event';
    }
}
