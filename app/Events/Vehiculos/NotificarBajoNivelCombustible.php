<?php

namespace App\Events\Vehiculos;

use App\Models\Empleado;
use App\Models\Notificacion;
use App\Models\Vehiculos\BitacoraVehicular;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Src\Config\TiposNotificaciones;

class NotificarBajoNivelCombustible implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public BitacoraVehicular $bitacora;
    public Notificacion $notificacion;
    public $url = '/control-vehiculos';
    public int $destinatario;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(BitacoraVehicular $bitacora, int $destinatario)
    {
        $this->bitacora = $bitacora;
        $this->destinatario = $destinatario;

        $this->notificacion = Notificacion::crearNotificacion($this->obtenerMensaje(), $this->url, TiposNotificaciones::BITACORA_VEHICULAR, auth()->user()->empleado->id, $destinatario, $bitacora, true);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('bitacora-vehiculo-' . $this->destinatario);
    }

    public function broadcastAs()
    {
        return 'bajo-nivel-combustible';
    }

    public function obtenerMensaje()
    {
        // return $this->bitacora->chofer->empleado->nombres . ' ' . $this->bitacora->chofer->empleado->apellidos . ' ha finalizado una bitácora de actividades con vehículo ' . $this->bitacora->vehiculo->placa . ' y tanque de combustible al finalizar el día es menor al ' . $this->bitacora->tanque_final . '%';
        return $this->bitacora->chofer->nombres . ' ' . $this->bitacora->chofer->apellidos . ' ha finalizado una bitácora de actividades con vehículo ' . $this->bitacora->vehiculo->placa . ' y tanque de combustible al finalizar el día es menor al ' . $this->bitacora->tanque_final . '%';
    }
}
