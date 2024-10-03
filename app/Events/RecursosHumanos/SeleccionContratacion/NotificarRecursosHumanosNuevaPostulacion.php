<?php

namespace App\Events\RecursosHumanos\RecursosHumanos\SeleccionContratacion;

use App\Models\Departamento;
use App\Models\Notificacion;
use App\Models\RecursosHumanos\SeleccionContratacion\Postulacion;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Src\Config\TiposNotificaciones;
use Throwable;

class NotificarRecursosHumanosNuevaPostulacion implements  ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $url = '/postulaciones';
    public int $destinatario;
    public Notificacion $notificacion;
    public int $postulacion_id;


    /**
     * Create a new event instance.
     *
     * @return void
     * @throws Throwable
     */
    public function __construct(int $postulacion_id)
    {
        $this->postulacion_id = $postulacion_id;
        $postulacion = Postulacion::find($postulacion_id);
        $dept_rrhh = Departamento::where('nombre', Departamento::DEPARTAMENTO_RRHH)->first();
        $this->destinatario = $dept_rrhh->responsable_id;
        $this->notificacion = Notificacion::crearNotificacion($this->obtenerMensaje(), $this->url, TiposNotificaciones::POSTULACION, $postulacion->user_id,$this->destinatario, $postulacion, false);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel
     */
    public function broadcastOn()
    {
        return new Channel('postulacion-realizada-'.$this->destinatario);
    }
    public function broadcastAs(){
        return 'postulacion-realizada-event';
    }

    private function obtenerMensaje(){
        $postulacion = Postulacion::find($this->postulacion_id);
        $persona = $postulacion->user_type === User::class ? $postulacion->user?->empleado : $postulacion->user?->persona;
        return  $persona->nombres . ' ' . $persona->apellidos . ' ha postulado a la vacante de '.$postulacion->vacante->nombre.'. Por favor, revisa y gestiona la postulación.';
    }
}
