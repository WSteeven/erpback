<?php

namespace App\Events\Tareas;

use App\Models\Empleado;
use App\Models\Notificacion;
use App\Models\Tareas\TransferenciaProductoEmpleado;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Src\Config\TiposNotificaciones;

class NotificarTransferenciaProductosRealizadaEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Notificacion $notificacion;
    public TransferenciaProductoEmpleado $transferencia;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(TransferenciaProductoEmpleado $transferencia)
    {
        $this->transferencia = $transferencia;

        $ruta = '/transferencia-producto-empleado';
        $emisor_id = $transferencia->empleado_origen_id;
        $destinatario_id = $transferencia->empleado_destino_id;

        $this->notificacion = Notificacion::crearNotificacion($this->obtenerMensaje(), $ruta, TiposNotificaciones::TRANSFERENCIA_PRODUCTOS, $emisor_id, $destinatario_id, $transferencia, true);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }

    private function obtenerMensaje() {
        $nombres_emisor = Empleado::extraerNombresApellidos(Empleado::find($this->transferencia->empleado_origen_id));
        $justificacion = $this->transferencia->justificacion;
        $codigo = 'TRANS_PROD-' . $this->transferencia->id;
        return $nombres_emisor . ' le ha realizado la transferencia con código ' . $codigo . ' y justificación: ' . $justificacion;
    }
}
