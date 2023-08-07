<?php

namespace App\Events;

use App\Models\Empleado;
use App\Models\Notificacion;
use App\Models\RecursosHumanos\NominaPrestamos\SolicitudPrestamoEmpresarial;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Src\Config\TiposNotificaciones;

class SolicitudPrestamoEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public Notificacion $notificacion;
    public SolicitudPrestamoEmpresarial $solicitud;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($solicitud)
    {
        $this->solicitud = $solicitud;
        $remitente =$this->obtenerEmpleado($this->solicitud->solicitante);
        $this->notificacion = Notificacion::crearNotificacion('Te han realizado una solicitud de Prestamo','/notificaciones', TiposNotificaciones::PRESTAMO_EMPRESARIAL, $remitente->id,2,$solicitud,true);
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
        $nombre_chanel ='solicitud-prestamo-empresarial'. 2;
        return new Channel($nombre_chanel );
    }
    public function broadcastAs()
    {
        return 'solicitud-prestamo-empresarial-event';
    }
}
