<?php

namespace App\Events\Medico;

use App\Models\Medico\SolicitudExamen;
use App\Models\Notificacion;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Src\Config\TiposNotificaciones;

class CambioFechaHoraSolicitudExamenEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private SolicitudExamen $solicitud_examen;
    private Notificacion $notificacion;
    private int $emisor;
    private int $destinatario;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(SolicitudExamen $solicitud_examen, int $emisor, int $destinatario)
    {
        $this->solicitud_examen = $solicitud_examen;
        $this->emisor = $emisor;
        $this->destinatario = $destinatario;

        $ruta = '/gestionar-pacientes';
        $mensaje = 'El autorizador ha cambiado la fecha u hora de asistencia a los laboratorios.';

        $this->notificacion = Notificacion::crearNotificacion($mensaje, $ruta, TiposNotificaciones::SOLICITUD_EXAMEN, $emisor, $destinatario, $solicitud_examen, true);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('solicitud-examen-tracker-' . $this->destinatario);
    }

    public function broadcastAs()
    {
        return 'solicitud-examen-event';
    }
}
