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
use Illuminate\Support\Facades\Log;
use Src\Config\TiposNotificaciones;
use Throwable;

class NotificarResultadosExamenesPostulanteRecursosHumanosEvent implements ShouldBroadcast
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
     * @throws Throwable|Exception
     */
    public function __construct(Examen $examen)
    {
        $this->examen = Examen::find($examen->postulacion_id);
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

    public function broadcastAs()
    {
        return 'resultados-examenes-postulante-event';
    }

    private function obtenerMensaje()
    {
        $postulacion = Postulacion::find($this->examen->postulacion_id);
        Log::channel('testing')->info('Log', ['postulacion es', $postulacion]);
        $persona = $postulacion->user_type === User::class ? $postulacion->user?->empleado : $postulacion->user?->persona;
        if ($this->examen->se_realizo_examen) {
            if ($this->examen->es_apto)
                return Empleado::extraerNombresApellidos(auth()->user()->empleado) . ' ha actualizado los exámenes médicos del Sr/Srta. ' . $persona->nombres . ' ' . $persona->apellidos . ' indicando que el postulante si es apto para la vacante, por lo tanto se actualiza su estado a contratado.';
            else
                return Empleado::extraerNombresApellidos(auth()->user()->empleado) . ' ha actualizado los exámenes médicos del Sr/Srta. ' . $persona->nombres . ' ' . $persona->apellidos . ' indicando que los resultados de los exámenes no son favorables para la vacante, por lo tanto será descartado del proceso';
        } else {
            return Empleado::extraerNombresApellidos(auth()->user()->empleado) . ' ha actualizado los exámenes médicos del Sr/Srta. ' . $persona->nombres . ' ' . $persona->apellidos . ' indicando que el postulante por voluntad propia y sin justificación alguna, NO SE REALIZO los exámenes en la fecha y hora planificadas, por lo tanto queda automáticamente descartado del proceso';
        }
    }
}
