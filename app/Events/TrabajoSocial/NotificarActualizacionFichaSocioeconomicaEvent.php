<?php

namespace App\Events\TrabajoSocial;

use App\Models\Departamento;
use App\Models\Empleado;
use App\Models\Notificacion;
use App\Models\RecursosHumanos\TrabajoSocial\FichaSocioeconomica;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Src\Config\TiposNotificaciones;
use Throwable;

class NotificarActualizacionFichaSocioeconomicaEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Notificacion $notificacion;
    public FichaSocioeconomica $ficha;
    public Departamento $departamento_ts; //departamento de trabajo social
    public string $ruta = 'fichas-socieconomicas';

    /**
     * Create a new event instance.
     *
     * @return void
     * @throws Throwable
     */
    public function __construct(FichaSocioeconomica $ficha)
    {
        $this->ficha = $ficha;
        $this->departamento_ts = Departamento::where('nombre', Departamento::DEPARTAMENTO_TRABAJO_SOCIAL)->first();
        $this->notificacion = Notificacion::crearNotificacion($this->obtenerMensaje(), $this->ruta, TiposNotificaciones::FICHA_SOCIOECONOMICA, null, $this->departamento_ts->responsable_id, $this->ficha, true);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel
     */
    public function broadcastOn()
    {
        return new Channel('notificar-actualizar-ficha-socioeconomica-'.$this->departamento_ts->responsable_id);
    }
    public function broadcastAs()
    {
        return 'ficha-socioeconomica-event';
    }

    private function obtenerMensaje()
    {
        return 'La ficha socioeconomica del empleado '. Empleado::extraerNombresApellidos($this->ficha->empleado).' ya tiene 1 año desde su última actualización, por favor actualiza la información del empleado en la ficha';
    }

}
