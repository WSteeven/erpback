<?php

namespace App\Events\RecursosHumanos;

use App\Models\Autorizacion;
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

class SolicitudVacacionEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public SolicitudVacacion $solicitud;
    public Notificacion $notificacion;
    private int $originador;
    private int $destinatario;

    /**
     * Create a new event instance.
     *
     * @return void
     * @throws Throwable|Exception
     */
    public function __construct($solicitud)
    {
        $ruta = '/solicitudes-vacaciones';
        $this->solicitud = $solicitud;
        switch ($solicitud->autorizacion_id) {
            case Autorizacion::PENDIENTE_ID:
                $mensaje = Empleado::extraerNombresApellidos($solicitud->empleado).' ha solicitado vacaciones';
                $this->originador = $solicitud->empleado_id;
                $this->destinatario = $solicitud->autorizador_id;
                break;
            case Autorizacion::APROBADO_ID:
                $mensaje = 'Te han aprobado vacaciones';
                $this->originador = $solicitud->autorizador_id;
                $this->destinatario = $solicitud->empleado_id;
                break;
            case Autorizacion::CANCELADO_ID:
                $mensaje = 'Te han rechazado vacaciones';
                $this->originador = $solicitud->autorizador_id;
                $this->destinatario = $solicitud->empleado_id;
                break;
            default:
                $mensaje = 'Tienes una vacacion por aprobar';
                $this->originador = $solicitud->empleado_id;
                $this->destinatario = $solicitud->autorizador_id;
                break;
        }
        $this->notificacion = Notificacion::crearNotificacion($mensaje, $ruta, TiposNotificaciones::VACACION, $this->originador, $this->destinatario, $this->solicitud, true);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel
     */
    public function broadcastOn()
    {


        return new Channel('vacacion-' . $this->destinatario);
    }


    public function broadcastAs()
    {
        return 'vacacion-event';
    }
}
