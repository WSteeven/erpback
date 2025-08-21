<?php

namespace App\Events\Medico;

use App\Models\Departamento;
use App\Models\Empleado;
use App\Models\Medico\CitaMedica;
use App\Models\Notificacion;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Src\Config\PusherEvents;
use Src\Config\TiposNotificaciones;
use Throwable;

class NotificarCitaMedicaADoctorEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private CitaMedica $citaMedica;
    private Notificacion $notificacion;
    private Departamento $departamento_medico;
    public string $ruta = '/citas-medicas';

    /**
     * Create a new event instance.
     *
     * @return void
     * @throws Throwable
     */
    public function __construct(CitaMedica $cita_medica)
    {
        $this->citaMedica = $cita_medica;
        $this->departamento_medico = Departamento::where('nombre', Departamento::DEPARTAMENTO_MEDICO)->first();

        $this->notificacion = Notificacion::crearNotificacion($this->obtenerMensaje(), $this->ruta, TiposNotificaciones::CITA_MEDICA, $this->citaMedica->paciente_id, $this->departamento_medico->responsable_id, $this->citaMedica, true);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel
     */
    public function broadcastOn()
    {
        return new Channel(PusherEvents::CITA_MEDICA->value."-tracker-".$this->departamento_medico->responsable_id);
    }

    public function broadcastAs()
    {
        return PusherEvents::CITA_MEDICA->value.'-event';
    }
    private function obtenerMensaje()
    {
        return 'El empleado ' . Empleado::extraerNombresApellidos($this->citaMedica->paciente) . ' ha solicitado una cita médica, por favor realice la gestión correspondiente';
    }
}
