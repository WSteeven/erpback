<?php

namespace App\Events\RecursosHumanos;

use App\Models\Empleado;
use App\Models\Notificacion;
use App\Models\RecursosHumanos\NominaPrestamos\SolicitudVacacion;
use Exception;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Src\Config\TiposNotificaciones;
use Throwable;

class SolicitudVacacionEventOld implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public SolicitudVacacion $vacacion;
    public Notificacion $notificacion;
    private int $id_wellington = 117;
    private int $id_veronica_valencia = 155;
    public int $jefeInmediato = 0;

    /**
     * Create a new event instance.
     *
     * @return void
     * @throws Throwable|Exception
     */
    public function __construct($vacacion)
    {
        $ruta = '/vacacion';
        $this->vacacion = $vacacion;
        $informativa = false;
        switch ($vacacion->estado) {
            case 1:
                $mensaje = $this->mostrar_mensaje($vacacion);
                break;
            case 2:
                $informativa = true;
                $mensaje = 'Te han aprobado vacaciones';
                break;
            case 3:
                $informativa = true;
                $mensaje = 'Te han rechazado vacaciones';
                break;
            default:
                $mensaje = 'Tienes una vacacion por aprobar';
                break;
        }
        $this->jefeInmediato = Empleado::find($vacacion->empleado_id)->jefe_id;
        if($this->jefeInmediato == $this->id_wellington) $this->jefeInmediato = $this->id_veronica_valencia;
        $destinatario = $vacacion->estado != 1 ?  $this->jefeInmediato : $vacacion->empleado_id;
        $remitente = $vacacion->estado != 1 ? $vacacion->empleado_id : $this->jefeInmediato;
        $this->notificacion = Notificacion::crearNotificacion($mensaje, $ruta, TiposNotificaciones::VACACION, $destinatario, $remitente, $vacacion, $informativa);
    }
    public function mostrar_mensaje($vacacion)
    {
        $empleado = Empleado::find($vacacion->empleado_id);
        return $empleado->nombres . ' ' . $empleado->apellidos . ' ha solicitado vacaciones ';
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel
     */
    public function broadcastOn()
    {
        $nombre_chanel =  $this->vacacion->estado == 1 ? 'vacacion-' . $this->jefeInmediato : 'vacacion-' . $this->vacacion->empleado_id;

        return new Channel($nombre_chanel);
    }


    public function broadcastAs()
    {
        return 'vacacion-event';
    }
}
