<?php

namespace App\Events\RecursosHumanos;

use App\Models\Empleado;
use App\Models\Notificacion;
use App\Models\RecursosHumanos\NominaPrestamos\PermisoEmpleado;
use Exception;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Src\Config\TiposNotificaciones;
use Throwable;

class PermisoEmpleadoEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public PermisoEmpleado $permisoEmpleado;
    public Notificacion $notificacion;
//    private int $id_wellington = 117;
//    private int $id_veronica_valencia = 155;
    public int $jefeInmediato = 0;

    /**
     * Create a new event instance.
     *
     * @return void
     * @throws Exception|Throwable
     */
    public function __construct($permisoEmpleado)
    {
        $ruta = '/permiso-nomina';
        $this->permisoEmpleado = $permisoEmpleado;
        $informativa = false;
        switch ($permisoEmpleado->estado_permiso_id) {
            case 1:
                $mensaje = $this->obtenerMensaje($permisoEmpleado);
                break;
            case 2:
                $informativa = true;
                $mensaje = 'Te han aprobado un permiso';
                break;
            case 3:
                $informativa = true;
                $mensaje = 'Te han rechazado un permiso por el siguiente motivo: ' . $permisoEmpleado->observacion;
                break;
            default:
                $mensaje = 'Tienes un permisoEmpleado por aprobar';
                break;
        }
        $this->jefeInmediato = Empleado::find($permisoEmpleado->empleado_id)->jefe_id;
//        if ($this->jefeInmediato == $this->id_wellington) $this->jefeInmediato = $this->id_veronica_valencia;
        $destinatario = $permisoEmpleado->estado_permiso_id != 1 ? $this->jefeInmediato : $permisoEmpleado->empleado_id;
        $remitente = $permisoEmpleado->estado_permiso_id != 1 ? $permisoEmpleado->empleado_id : $this->jefeInmediato;
        $this->notificacion = Notificacion::crearNotificacion($mensaje, $ruta, TiposNotificaciones::PERMISO_EMPLEADO, $destinatario, $remitente, $permisoEmpleado, $informativa);
    }

    public function obtenerMensaje($permiso)
    {
        $empleado = Empleado::find($permiso->empleado_id);
        return $empleado->nombres . ' ' . $empleado->apellidos . ' ha solicitado un permiso por el siguiente motivo: ' . $permiso->justificacion;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel
     */
    public function broadcastOn()
    {
        $nombre_chanel = $this->permisoEmpleado->estado_permiso_id == 1 ? 'permiso-empleado-' . $this->jefeInmediato : 'permiso-empleado-' . $this->permisoEmpleado->empleado_id;
        return new Channel($nombre_chanel);
    }


    public function broadcastAs()
    {
        return 'permiso-empleado-event';
    }
}
