<?php

namespace App\Events\RecursosHumanos\SeleccionContratacion;

use App\Models\Departamento;
use App\Models\Empleado;
use App\Models\Notificacion;
use App\Models\RecursosHumanos\SeleccionContratacion\Examen;
use App\Models\RecursosHumanos\SeleccionContratacion\Postulacion;
use App\Models\User;
use Exception;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Src\Config\TiposNotificaciones;
use Throwable;

class NotificarAgendamientoExamenesPostulanteRecursosHumanosEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $url = '/postulaciones';
    public int $destinatario;
    public Notificacion $notificacion;
    public Examen $examen;

    /**
     * Create a new event instance.
     *
     * @return void
     * @throws Exception|Throwable
     */
    public function __construct(Postulacion $postulacion)
    {
        $this->examen = Examen::find($postulacion->id);
        $dept_rrhh = Departamento::where('nombre', Departamento::DEPARTAMENTO_RRHH)->first();
        $this->destinatario = $dept_rrhh->responsable_id;
        $this->notificacion = Notificacion::crearNotificacion($this->obtenerMensaje(), $this->url, TiposNotificaciones::CANDIDATO_SELECCIONADO, auth()->user()->empleado->id, $this->destinatario, $this->examen, true);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel
     */
    public function broadcastOn()
    {
        return new Channel('postulante-seleccionado-' . $this->destinatario);
    }
    public function broadcastAs(){
        return 'postulante-examenes-agendados-event';
    }

    private function obtenerMensaje()
    {
        $postulacion = Postulacion::find($this->examen->postulacion_id);
        $persona = $postulacion->user_type === User::class ? $postulacion->user?->empleado : $postulacion->user?->persona;
        return Empleado::extraerNombresApellidos(auth()->user()->empleado) . ' ha agendado unos examenes médicos para el Sr/Srta. ' . $persona->nombres . ' ' . $persona->apellidos . ' a realizarse el ' . date('Y-m-d H:i',strtotime($this->examen->fecha_hora)) . ' en la ciudad de ' . $this->examen->canton->canton . ' en el laboratorio ' . $this->examen->laboratorio;
    }
}
