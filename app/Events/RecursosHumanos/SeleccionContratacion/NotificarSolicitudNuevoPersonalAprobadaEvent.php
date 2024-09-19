<?php

namespace App\Events\RecursosHumanos\SeleccionContratacion;

use App\Models\Departamento;
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
use Throwable;

class NotificarSolicitudNuevoPersonalAprobadaEvent implements ShouldBroadcast
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
     * @throws Throwable|Exception
     */
    public function __construct($solicitud)
    {
        $this->solicitud = $solicitud;

        $departamento = Departamento::where('nombre', Departamento::DEPARTAMENTO_RRHH)->first();

        $this->destinatario = $departamento->responsable_id;
        $this->notificacion = Notificacion::crearNotificacion($this->obtenerMensaje(), $this->url, TiposNotificaciones::SOLICITUD_NUEVO_EMPLEADO, $this->solicitud->autorizador_id, $this->destinatario, $this->solicitud, false);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel
     */
    public function broadcastOn()
    {
        return new Channel('solicitud-personal-aprobada-'.$this->destinatario);
    }
    public function broadcastAs()
    {
        return 'solicitud-personal-event';
    }

    private  function obtenerMensaje()
    {
        return Empleado::extraerNombresApellidos($this->solicitud->autorizador).' ha aprobado la solicitud de personal N°'.$this->solicitud->id.', por favor configura y publica la vacante';
    }
}
