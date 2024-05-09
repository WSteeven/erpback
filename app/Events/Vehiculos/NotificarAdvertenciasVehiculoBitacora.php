<?php

namespace App\Events\Vehiculos;

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

class NotificarAdvertenciasVehiculoBitacora implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public BitacoraVehicular $bitacora;
    public Notificacion $notificacion;
    public $url = '/control-vehiculos';
    public int $destinatario;
    public array $advertencias;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($bitacora, $advertencias, $destinatario_id)
    {
        $this->bitacora = $bitacora;
        $this->advertencias = $advertencias;
        $this->destinatario = $destinatario_id;

        $this->notificacion = Notificacion::crearNotificacion($this->obtenerMensaje(), $this->url, TiposNotificaciones::BITACORA_VEHICULAR_ADVERTENCIA, $this->bitacora->chofer_id, $destinatario_id, $bitacora, true);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('bitacora-vehiculo-advertencia-' . $this->destinatario);
    }

    public function broadcastAs()
    {
        return 'bitacora-vehiculo-advertencia';
    }

    public function obtenerMensaje()
    {

        $mensaje = '';

        foreach ($this->advertencias as $key => $value) {
            $mensaje .= "$value en $key, ";
        }

        // Eliminar la coma y el espacio extra al final
        $mensaje = rtrim($mensaje, ', ');

        return 'Se encontraron las siguientes advertencias en el vehículo ' . $this->bitacora->vehiculo->placa . ': ' . $mensaje . '. Por favor revisa la bitacora N° ' . $this->bitacora->id;
    }
}
