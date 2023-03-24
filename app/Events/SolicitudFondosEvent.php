<?php

namespace App\Events;

use App\Models\Empleado;
use App\Models\FondosRotativos\Gasto\GastoCoordinador;
use App\Models\Notificacion;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Src\Config\TiposNotificaciones;

class SolicitudFondosEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public GastoCoordinador $solicitud;
    public Notificacion $notificacion;
    public int $destinatario;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($solicitud,$contabilidad)
    {
        $this->solicitud = $solicitud;
        $this->destinatario = $contabilidad->id ;
        $remitente = $this->obtenerEmpleado($this->solicitud->id_usuario)->id;
        $this->notificacion = Notificacion::crearNotificacion('Te han realizado una solicitud de Fondos Rotativos','/notificaciones', TiposNotificaciones::AUTORIZACION_GASTO, $remitente,$contabilidad->empleado->id);
    }

    public function obtenerEmpleado($id)
    {
        return Empleado::where('usuario_id',$id)->first();
    }

   /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        $nombre_chanel ='solicitud-fondos-'. $this->destinatario;
        Log::channel('testing')->info('Log', ['nombre canal',$nombre_chanel]);
        return new Channel($nombre_chanel );
    }
    public function broadcastAs()
    {
        return 'solicitud-fondos-event';
    }
}
