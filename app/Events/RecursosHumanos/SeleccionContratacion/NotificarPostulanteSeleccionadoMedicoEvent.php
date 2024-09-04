<?php

namespace App\Events\RecursosHumanos\SeleccionContratacion;

use App\Models\Departamento;
use App\Models\Notificacion;
use App\Models\RecursosHumanos\SeleccionContratacion\Postulacion;
use Exception;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Src\Config\TiposNotificaciones;

class NotificarPostulanteSeleccionadoMedicoEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $url = '/postulaciones';
    public int $destinatario;
    public Notificacion $notificacion;
    public Postulacion $postulacion;

    /**
     * Create a new event instance.
     *
     * @return void
     * @throws Exception
     */
    public function __construct(Postulacion $postulacion)
    {
        $this->postulacion = $postulacion;
        $dept_medico = Departamento::where('nombre', Departamento::DEPARTAMENTO_MEDICO)->first();
        $this->destinatario = $dept_medico->responsable_id;
        Log::channel('testing')->info('Log', ['constructor del event', $postulacion, $this->destinatario]);
        $this->notificacion = Notificacion::crearNotificacion($this->obtenerMensaje(), $this->url, TiposNotificaciones::CANDIDATO_SELECCIONADO, 199, 116, $this->postulacion, true);
        Log::channel('testing')->info('Log', ['auth es', auth()->user()]);
        Log::channel('testing')->info('Log', ['empleado es', auth()->user()->empleado]);
        Log::channel('testing')->info('Log', ['despues de crear la notificacion', $this->notificacion]);
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
        return 'postulante-seleccionado-event';
    }

    private function obtenerMensaje()
    {
        return "RRHH informa: ha sido seleccionado un potencial candidato para una vacante, por favor realizar los respectivos exámenes médicos.";
    }
}
