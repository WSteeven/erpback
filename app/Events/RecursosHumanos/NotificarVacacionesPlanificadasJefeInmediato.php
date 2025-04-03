<?php

namespace App\Events\RecursosHumanos;

use App\Models\Empleado;
use App\Models\Notificacion;
use App\Models\RecursosHumanos\NominaPrestamos\PlanVacacion;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Src\Config\TiposNotificaciones;
use Throwable;

class NotificarVacacionesPlanificadasJefeInmediato implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Notificacion $notificacion;
    public PlanVacacion $plan;
    public  string $ruta='planes-vacaciones';
    public int $jefeId;

    /**
     * Create a new event instance.
     *
     * @return void
     * @throws Throwable
     */
    public function __construct($plan)
    {
        $this->plan = $plan;
        $this->jefeId = $plan->empleado->jefe_id;
        $this->notificacion = Notificacion::crearNotificacion($this->obtenerMensaje(),$this->ruta, TiposNotificaciones::ASIGNACION_VEHICULO, null, $this->plan->empleado_id,$this->plan, true);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel
     */
    public function broadcastOn()
    {
        return new Channel('vacaciones-planificadas-'.$this->jefeId);
    }
    public static function broadcastAs()
    {
        return 'vacaciones-planificadas-event';
    }

    private function obtenerMensaje(){
        return 'El empleado '.Empleado::extraerNombresApellidos($this->plan->empleado).' tiene vacaciones planificadas para el día de mañana. Por favor realiza la gestión correspondiente para reprogramar las vacaciones o no hagas nada en caso de estar de acuerdo con la fecha de vacaciones';
    }
}
