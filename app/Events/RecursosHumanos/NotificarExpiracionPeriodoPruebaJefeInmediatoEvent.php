<?php

namespace App\Events\RecursosHumanos;

use App\Models\Empleado;
use App\Models\Notificacion;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Src\Config\TiposNotificaciones;
use Throwable;

class NotificarExpiracionPeriodoPruebaJefeInmediatoEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Notificacion $notificacion;
    public string $ruta = '/';
    public int $jefeId;
    public int $dias_transcurridos;
    public Empleado $empleado;

    /**
     * Create a new event instance.
     *
     * @return void
     * @throws Throwable
     */
    public function __construct(Empleado $empleado, int $dias_transcurridos)
    {
        $this->empleado = $empleado;
        $this->dias_transcurridos = $dias_transcurridos;
        $this->jefeId = $empleado->id;
        $this->notificacion = Notificacion::crearNotificacion($this->obtenerMensaje(), $this->ruta, TiposNotificaciones::DEVOLUCION, null, $this->jefeId, $empleado, true);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel
     */
    public function broadcastOn()
    {
        return new Channel('expiracion-periodo-prueba-empleado-'.$this->jefeId);
    }
    public function broadcastAs()
    {
        return 'expiracion-periodo-prueba-empleado-event';
    }

    public function obtenerMensaje()
    {
        return 'RECORDATORIO: El empleado '.Empleado::extraerNombresApellidos($this->empleado).' tiene '.$this->dias_transcurridos.' días laborados, por favor realiza la evaluación de desempeño a este empleado antes de los 90 días';
    }
}
