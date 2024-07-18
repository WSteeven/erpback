<?php

namespace App\Events\RecursosHumanos\SeleccionContratacion;

use App\Models\Autorizacion;
use App\Models\Empleado;
use App\Models\Notificacion;
use App\Models\RecursosHumanos\SeleccionContratacion\SolicitudPersonal;
use Exception;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Src\Config\TiposNotificaciones;

class NotificarSolicitudNuevoPersonalEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public SolicitudPersonal $solicitud;
    public Notificacion $notificacion;
    public string $url = '/solicitudes-puestos';
    public int $destinatario;

    /**
     * Create a new event instance.
     *
     * @return void
     * @throws Exception
     */
    public function __construct($solicitud)
    {
        $this->solicitud = $solicitud;

        $this->notificacion = $this->obtenerNotificacion();
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel
     */
    public function broadcastOn()
    {
        return new Channel('solicitud-personal-' . $this->destinatario);
    }

    public function broadcastAs()
    {
        return 'solicitud-personal-event';
    }

    /**
     * @throws Exception
     */
    private function obtenerNotificacion()
    {
        switch ($this->solicitud->autorizacion_id) {
            case Autorizacion::PENDIENTE_ID:
                $this->destinatario = $this->solicitud->solicitante_id;
                $msg = Empleado::extraerNombresApellidos($this->solicitud->solicitante) . ' ha realizado un requerimiento de personal para su equipo de trabajo. Por favor, aprueba o cancela la solicitud';
                return Notificacion::crearNotificacion($msg, $this->url, TiposNotificaciones::SOLICITUD_NUEVO_EMPLEADO, $this->solicitud->solicitante_id, $this->solicitud->autorizador_id, $this->solicitud, false);
            case Autorizacion::APROBADO_ID:
                $this->destinatario = $this->solicitud->autorizador_id;
                $msg = Empleado::extraerNombresApellidos($this->solicitud->autorizador) . ' ha aprobado tu requerimiento de personal. RRHH publicará la vacante en el sistema';
                return Notificacion::crearNotificacion($msg, $this->url, TiposNotificaciones::SOLICITUD_NUEVO_EMPLEADO, $this->solicitud->autorizador_id, $this->solicitud->solicitante_id, $this->solicitud, false);
            default: // cancelado
                $this->destinatario = $this->solicitud->autorizador_id;
                $msg = Empleado::extraerNombresApellidos($this->solicitud->autorizador) . ' ha aprobado tu requerimiento de personal. RRHH publicará la vacante en el sistema';
                return Notificacion::crearNotificacion($msg, $this->url, TiposNotificaciones::SOLICITUD_NUEVO_EMPLEADO, $this->solicitud->autorizador_id, $this->solicitud->solicitante_id, $this->solicitud, false);
        }

    }
}
