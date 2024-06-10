<?php

namespace App\Events;

use App\Models\Departamento;
use App\Models\Notificacion;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Src\Config\TiposNotificaciones;

class PermisoNotificacionEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
 public $permiso;
 public Notificacion $notificacion;
 public  $jefeInmediato = 0;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($permiso)
    {
        $ruta = '/empleado';
        $this->permiso = $permiso;
        $informativa = true;
        $this->jefeInmediato = Departamento::where('nombre', Departamento::DEPARTAMENTO_RRHH)->first()->responsable_id;
        $destinatario = $permiso->empleado_id ;
        $remitente =$this->jefeInmediato ;
        $mensaje = $this->permiso->empleado_info->nombres.' '. $this->permiso->empleado_info->apellidos. ' Ha pedido permiso de ' . $this->permiso->fecha_hora_inicio. ' hasta '. $this->permiso->fecha_hora_fin.' con la siguiente justificacion: '. $this->permiso->justificacion;
        $this->notificacion = Notificacion::crearNotificacion($mensaje, $ruta, TiposNotificaciones::PERMISO_EMPLEADO, $destinatario, $remitente, $permiso, $informativa);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        $nombre_chanel ='notificacion-permiso-' . $this->jefeInmediato;
        return new Channel($nombre_chanel);
    }
    public function broadcastAs()
    {
        return 'notificacion-permiso-event';
    }
}
