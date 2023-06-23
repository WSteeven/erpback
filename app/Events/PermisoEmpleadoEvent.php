<?php

namespace App\Events;

use App\Http\Resources\PermisoResource;
use App\Models\Notificacion;
use App\Models\RecursosHumanos\NominaPrestamos\PermisoEmpleado;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Src\Config\TiposNotificaciones;

class PermisoEmpleadoEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public PermisoEmpleado $permisoEmpleado;
    public Notificacion $notificacion;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($permisoEmpleado)
    {
        $ruta = $permisoEmpleado->estado == 3? '/autorizar-permisoEmpleado':'/permisoEmpleado';
        $this->permisoEmpleado = $permisoEmpleado;
        $informativa = false;
        switch ($permisoEmpleado->estado) {
            case 1:
                $informativa = true;
               $mensaje = 'Te han aprobado un permiso';
                break;
            case 2:
                $informativa = true;
                $mensaje = 'Te han rechazado un permiso por el siguiente motivo: '.$permisoEmpleado->observacion;
                break;
            case 3:
                $mensaje = $this->mostrar_mensaje($permisoEmpleado);
                break;
            default:
            $mensaje = 'Tienes un permisoEmpleado por aprobar';
                break;
        }
        $destinatario = $permisoEmpleado->estado!=3? $permisoEmpleado->aut_especial:$permisoEmpleado->id_usuario;
        $remitente = $permisoEmpleado->estado!=3? $permisoEmpleado->id_usuario:$permisoEmpleado->aut_especial;
      $this->notificacion = Notificacion::crearNotificacion($mensaje,$ruta, TiposNotificaciones::PERMISO_EMPLEADO, $destinatario, $remitente,$permisoEmpleado,$informativa);
    }
    public function mostrar_mensaje($gasto)
    {
        $empleado = PermisoEmpleado::find($gasto->id_usuario);
        $modelo = new PermisoResource($gasto);
        $detalle = $modelo->observacion;
        $mensaje = $empleado->nombres.' '.$empleado->apellidos.' ha solicitado un permiso por el siguiente motivo: '.$gasto->motivo;
        return $mensaje;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
