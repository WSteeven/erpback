<?php

namespace App\Events;

use App\Http\Resources\PermisoResource;
use App\Models\Empleado;
use App\Models\Notificacion;
use App\Models\RecursosHumanos\NominaPrestamos\PermisoEmpleado;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Src\Config\TiposNotificaciones;

class PermisoEmpleadoEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public PermisoEmpleado $permisoEmpleado;
    public Notificacion $notificacion;
    public  $jefeInmediato=0;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($permisoEmpleado)
    {
        $ruta =  '/permiso-nomina';
        $this->permisoEmpleado = $permisoEmpleado;
        $informativa = false;
        switch ($permisoEmpleado->estado_permiso_id) {
            case 1:
                $mensaje = $this->mostrar_mensaje($permisoEmpleado);
                break;
            case 2:
                $informativa = true;
               $mensaje = 'Te han aprobado un permiso';
                break;
            case 3:
                $informativa = true;
                $mensaje = 'Te han rechazado un permiso por el siguiente motivo: '.$permisoEmpleado->observacion;
                break;
            default:
            $mensaje = 'Tienes un permisoEmpleado por aprobar';
                break;
        }
        $this->jefeInmediato = Empleado::where('id',$permisoEmpleado->empleado_id)->first()->jefe_id;
        $destinatario = $permisoEmpleado->estado_permiso_id!=1?  $this->jefeInmediato:$permisoEmpleado->empleado_id;
        $remitente = $permisoEmpleado->estado_permiso_id!=1? $permisoEmpleado->empleado_id: $this->jefeInmediato;
      $this->notificacion = Notificacion::crearNotificacion($mensaje,$ruta, TiposNotificaciones::PERMISO_EMPLEADO, $destinatario, $remitente,$permisoEmpleado,$informativa);
    }
    public function mostrar_mensaje($gasto)
    {
        $empleado = Empleado::find($gasto->empleado_id);
        $mensaje = $empleado->nombres.' '.$empleado->apellidos.' ha solicitado un permiso por el siguiente motivo: '.$gasto->justificacion;
        return $mensaje;
    }

   /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        $nombre_chanel =  $this->permisoEmpleado->estado_permiso_id==1? 'permiso-empleado-'. $this->jefeInmediato:'permiso-empleado-'. $this->permisoEmpleado->empleado_id;
        return new Channel($nombre_chanel );
    }


    public function broadcastAs()
    {
        return 'permiso-empleado-event';
    }
}
