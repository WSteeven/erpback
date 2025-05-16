<?php

namespace App\Events\Vehiculos;

use App\Mail\Notificar;
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

class NotificarDiferenciaKmToAdmin implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public BitacoraVehicular $bitacora;
    public Notificacion $notificacion;
    public int  $destinatario;
    public int  $diferencia;
    public $url = '/control-vehiculos';
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(BitacoraVehicular $bitacora, int $destinatario, int $diferencia)
    {
        $this->bitacora = $bitacora;
        $this->destinatario = $destinatario;
        $this->diferencia = $diferencia;

        $this->notificacion = Notificacion::crearNotificacion($this->obtenerMensaje(), $this->url, TiposNotificaciones::BITACORA_VEHICULAR, auth()->user()->empleado_id, $this->destinatario, $this->bitacora, true);
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
        return 'diferencia-km';
    }

    public function obtenerMensaje()
    {
        return 'El empleado ' . $this->bitacora->chofer->nombres . ' ' . $this->bitacora->chofer->apellidos . ' ha creado una bitácora N° ' . $this->bitacora->id . ' con una diferencia de KM  de ' . $this->diferencia . ' respecto al Km final de la bitácora anterior. Por favor revisa esta información.';
    }
}
