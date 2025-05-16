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

        $ruta = '/aceptar-transferencia-producto';
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
        return new Channel('transferencia-productos-realizada-tracker-' . $this->transferencia->empleado_destino_id);
    }

    public function broadcastAs()
    {
        return 'transferencia-productos-realizada-event';
    }

    private function obtenerMensaje()
    {
        $nombres_emisor = Empleado::extraerNombresApellidos(Empleado::find($this->transferencia->empleado_origen_id));
        $nombres_autorizador = Empleado::extraerNombresApellidos(Empleado::find($this->transferencia->autorizador_id));
        $justificacion = $this->transferencia->justificacion;
        $codigo = 'TRANSF-' . $this->transferencia->id;
        return 'Aceptación de transferencia PENDIENTE. ' . $nombres_emisor . ' le ha realizado la transferencia ' . $codigo . '. Justificación: ' . $justificacion . ' autorizado por ' . $nombres_autorizador . '.';
    }
}
