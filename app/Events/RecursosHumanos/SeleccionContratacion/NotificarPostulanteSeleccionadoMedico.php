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
use Src\Config\TiposNotificaciones;

class NotificarPostulanteSeleccionadoMedico implements  ShouldBroadcast
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
        $this->notificacion = Notificacion::crearNotificacion($this->obtenerMensaje(), $this->url,TiposNotificaciones::CANDIDATO_SELECCIONADO,auth()->user()->empleado->id, $this->destinatario, $this->postulacion, true);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel
     */
    public function broadcastOn()
    {
        return new Channel('postulante-seleccionado-'. $this->destinatario);
    }

    public function broadcastAs(){
        return 'postulante-seleccionado-event';
    }

    private function obtenerMensaje(){
        return "RRHH informa: ha sido seleccionado un potencial candidato para una vacante, por favor realizar los respectivos exámenes médicos.";
    }
}
