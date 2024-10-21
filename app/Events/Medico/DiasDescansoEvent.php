<?php

namespace App\Events\RecursosHumanos\Medico;

use App\Models\Empleado;
use App\Models\Medico\ConsultaMedica;
use App\Models\Notificacion;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Src\Config\PusherEvents;
use Src\Config\TiposNotificaciones;

class DiasDescansoEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private ConsultaMedica $consulta_medica;
    private Notificacion $notificacion;
    private int $emisor;
    private int $destinatario;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(ConsultaMedica $consulta_medica, int $emisor, int $destinatario)
    {
        $this->consulta_medica = $consulta_medica;
        $this->emisor = $emisor;
        $this->destinatario = $destinatario;

        $ruta = '/';
        $mensaje = 'Al paciente ' . (isset($consulta_medica->citaMedica?->paciente) ? Empleado::extraerNombresApellidos($consulta_medica->citaMedica?->paciente) : Empleado::extraerNombresApellidos($consulta_medica->registroEmpleadoExamen?->empleado)) . ' se le ha asignado ' . $consulta_medica->dias_descanso . ' días de descanso.';
        $this->notificacion = Notificacion::crearNotificacion($mensaje, $ruta, TiposNotificaciones::DIAS_DESCANSO, $emisor, $destinatario, $consulta_medica, true);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        // return new PrivateChannel('channel-name');
        return new PrivateChannel(PusherEvents::DIAS_DESCANSO . '-tracker-' . $this->destinatario);
    }

    public function broadcastAs()
    {
        return PusherEvents::DIAS_DESCANSO . '-event';
    }
}
