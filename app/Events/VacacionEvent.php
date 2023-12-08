<?php

namespace App\Events;

use App\Models\Empleado;
use App\Models\Notificacion;
use App\Models\RecursosHumanos\NominaPrestamos\Vacacion;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Src\Config\TiposNotificaciones;

class VacacionEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Vacacion $vacacion;
    public Notificacion $notificacion;
    public  $jefeInmediato = 0;
    /**
     * Create a new event instance.
     *
     * @return void
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
        $this->jefeInmediato = Empleado::where('id', $vacacion->empleado_id)->first()->jefe_id;
        $destinatario = $vacacion->estado != 1 ?  $this->jefeInmediato : $vacacion->empleado_id;
        $remitente = $vacacion->estado != 1 ? $vacacion->empleado_id : $this->jefeInmediato;
        $this->notificacion = Notificacion::crearNotificacion($mensaje, $ruta, TiposNotificaciones::VACACION, $destinatario, $remitente, $vacacion, $informativa);
    }
    public function mostrar_mensaje($vacacion)
    {
        $empleado = Empleado::find($vacacion->empleado_id);
        $mensaje = $empleado->nombres . ' ' . $empleado->apellidos . ' ha solicitado vacaciones ';
        return $mensaje;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        $nombre_chanel =  $this->vacacion->estado == 1 ? 'vacacion-' . $this->jefeInmediato : 'vacacion-' . $this->vacacion->empleado_id;
        Log::channel('testing')->info('Log', ['Nombre del canal', $nombre_chanel]);

        return new Channel($nombre_chanel);
    }


    public function broadcastAs()
    {
        return 'vacacion-event';
    }
}
