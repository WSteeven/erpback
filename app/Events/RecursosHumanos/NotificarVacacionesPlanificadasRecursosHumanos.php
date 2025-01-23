<?php

namespace App\Events\RecursosHumanos;

use App\Models\Departamento;
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

class NotificarVacacionesPlanificadasRecursosHumanos implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Notificacion $notificacion;
    public PlanVacacion $plan;
    public Departamento $departamento_rrhh;
    public string $ruta = 'planes-vacaciones';

    /**
     * Create a new event instance.
     *
     * @return void
     * @throws Throwable
     */
    public function __construct($plan)
    {
        $this->plan = $plan;
        $this->departamento_rrhh = Departamento::where('nombre', Departamento::DEPARTAMENTO_RRHH)->first();
        $this->notificacion = Notificacion::crearNotificacion($this->obtenerMensaje(), $this->ruta, TiposNotificaciones::ASIGNACION_VEHICULO, null, $this->departamento_rrhh->responsable_id, $this->plan, false);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel
     */
    public function broadcastOn()
    {
        return new Channel('vacaciones-planificadas-' . $this->departamento_rrhh->responsable_id);
    }

    public static function broadcastAs()
    {
        return 'vacaciones-planificadas-event';
    }

    private function obtenerMensaje()
    {
        return 'Notificación: El empleado ' . Empleado::extraerNombresApellidos($this->plan->empleado) . ' tiene vacaciones programadas para el día de mañana. Por favor tenga en cuenta esto de acuerdo a la planificación con el personal';
    }
}
