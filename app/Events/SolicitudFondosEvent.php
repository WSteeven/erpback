<?php

namespace App\Events;

use App\Models\Departamento;
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
    public function __construct($solicitud)
    {
        $contabilidad = Departamento::where('id',2)->first();
        $this->solicitud = $solicitud;
        $this->destinatario = $contabilidad->responsable_id ;
        $remitente = $this->solicitud->id_usuario;
        $empleado_remitente = $this->obtenerEmpleado($remitente);
        Log::channel('testing')->info('Log', ['error',$empleado_remitente]);

        $this->notificacion = Notificacion::crearNotificacion ($empleado_remitente->nombres.' '. $empleado_remitente->apellidos.' Te han realizado una solicitud de Fondos Rotativos por un monto de $'.$solicitud->monto,'/notificaciones', TiposNotificaciones::AUTORIZACION_GASTO, $remitente,$contabilidad->responsable_id,$solicitud,true);
    }
    public function obtenerEmpleado($id)
    {
        return Empleado::where('id',$id)->first();
    }

   /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        $nombre_chanel ='solicitud-fondos-'. $this->destinatario;
        return new Channel($nombre_chanel );
    }
    public function broadcastAs()
    {
        return 'solicitud-fondos-event';
    }
}
