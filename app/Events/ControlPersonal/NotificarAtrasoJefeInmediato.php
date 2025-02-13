<?php

namespace App\Events\ControlPersonal;

use App\Models\ControlPersonal\Atraso;
use App\Models\Empleado;
use App\Models\Notificacion;
use Carbon\CarbonInterval;
use Exception;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Src\Config\TiposNotificaciones;
use Throwable;

class NotificarAtrasoJefeInmediato implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;


    public Atraso $atraso;
    public Notificacion $notificacion;
    private string $ruta = '/atrasos';

    /**
     * Create a new event instance.
     *
     * @return void
     * @throws Throwable
     */
    public function __construct(Atraso $atraso)
    {
        $this->atraso = $atraso;

        $this->notificacion = Notificacion::crearNotificacion($this->obtenerMensaje(), $this->ruta, TiposNotificaciones::ATRASO, $this->atraso->empleado_id, $this->atraso->empleado->jefe_id, $this->atraso, true);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel
     */
    public function broadcastOn()
    {
        return new Channel('atrasos-tracker-' . $this->atraso->empleado->jefe_id);
    }

    public function broadcastAs(){
        return 'atrasos-event';
    }

    /**
     * @throws Exception
     */
    private function obtenerMensaje()
    {
        return 'El empleado ' . Empleado::extraerNombresApellidos($this->atraso->empleado) . ' que consta bajo tu cargo,
        ha llegado tarde ' . CarbonInterval::seconds($this->atraso->segundos_atraso)->cascade()->forHumans() . ' en su ' .
        $this->atraso->ocurrencia. 'el '.$this->atraso->fecha_atraso.'. Por favor revisa y justifica el atraso';
    }
}
