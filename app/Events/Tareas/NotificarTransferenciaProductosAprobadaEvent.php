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

/**
 * Evento que se emite cuando una transferencia de productos ha sido aprobada.
 * Se notifica a encargado de bodega y jefe inmediato.
 */
class NotificarTransferenciaProductosAprobadaEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Notificacion $notificacion;
    public TransferenciaProductoEmpleado $transferencia;
    public $destinatario_id;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(TransferenciaProductoEmpleado $transferencia, $destinatario_id)
    {
        $this->transferencia = $transferencia;

        $ruta = '/transferencia-producto-empleado';
        $this->destinatario_id = $destinatario_id;

        $this->notificacion = Notificacion::crearNotificacion($this->obtenerMensaje(), $ruta, TiposNotificaciones::TRANSFERENCIA_PRODUCTOS, $transferencia->empleado_destino_id, $destinatario_id, $transferencia, true);
    }

    /**Fno-shadow
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('transferencia-productos-realizada-tracker-' . $this->destinatario_id);
    }

    public function broadcastAs()
    {
        return 'transferencia-productos-realizada-event';
    }

    private function obtenerMensaje()
    {
        $nombres_emisor = Empleado::extraerNombresApellidos(Empleado::find($this->transferencia->empleado_origen_id));
        $nombres_destinatario = Empleado::extraerNombresApellidos(Empleado::find($this->transferencia->empleado_destino_id));
        $nombres_autorizador = Empleado::extraerNombresApellidos(Empleado::find($this->transferencia->autorizador_id));
        $justificacion = $this->transferencia->justificacion;
        $codigo = 'TRANSF-' . $this->transferencia->id;
        return 'Transferencia APROBADA exitosamente!. ' . $nombres_destinatario . ' ha aceptado la transferencia realizada por ' . $nombres_emisor . '. Código: ' . $codigo . '. Justificación: ' . $justificacion . '. Autorizado por ' . $nombres_autorizador . '.';
    }
}
