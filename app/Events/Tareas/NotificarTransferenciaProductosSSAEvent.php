<?php

namespace App\Events\Tareas;

use App\Models\Autorizacion;
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
use Log;
use Src\Config\TiposNotificaciones;

class NotificarTransferenciaProductosSSAEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Notificacion $notificacion;
    public TransferenciaProductoEmpleado $transferencia;
    public int $destinatario_id;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(TransferenciaProductoEmpleado $transferencia, int $destinatario_id)
    {
        $this->transferencia = $transferencia;

        $ruta = '/aceptar-transferencia-producto';
        $emisor_id = $transferencia->empleado_origen_id;
        $this->destinatario_id = $destinatario_id;

        Log::channel('testing')->info('Log', ['Transf: ', $transferencia]);
        Log::channel('testing')->info('Log', ['Auth: ', $transferencia['autorizacion_id']]);

        if ($this->transferencia['autorizacion_id'] === Autorizacion::VALIDADO_ID) {
            $this->notificacion = Notificacion::crearNotificacion($this->getMessageValidado(), $ruta, TiposNotificaciones::TRANSFERENCIA_PRODUCTOS, $emisor_id, $destinatario_id, $transferencia, true);
        }
        if ($this->transferencia['autorizacion_id'] === Autorizacion::APROBADO_ID) {
            $this->notificacion = Notificacion::crearNotificacion($this->getMessageAprobado(), $ruta, TiposNotificaciones::TRANSFERENCIA_PRODUCTOS, $emisor_id, $destinatario_id, $transferencia, true);
        }
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        // return new PrivateChannel('channel-name');
        return new Channel('transferencia-productos-realizada-tracker-' . $this->destinatario_id);
    }

    public function broadcastAs()
    {
        return 'transferencia-productos-realizada-event';
    }

    private function getMessageValidado()
    {
        $nombres_emisor = Empleado::extraerNombresApellidos(Empleado::find($this->transferencia->empleado_origen_id));
        $nombres_autorizador = Empleado::extraerNombresApellidos(Empleado::find($this->transferencia->autorizador_id));
        $justificacion = $this->transferencia->justificacion;
        $codigo = 'TRANSF-' . $this->transferencia->id;
        return 'El empleado ' . $nombres_emisor . ' va a transferir EPPS. Cód.transferencia: ' . $codigo . '. Justificación: ' . $justificacion . ' y será autorizado por ' . $nombres_autorizador . '. **Esta notificación es sólo informativa.';
    }

    private function getMessageAprobado()
    {
        $nombres_emisor = Empleado::extraerNombresApellidos(Empleado::find($this->transferencia->empleado_origen_id));
        $nombres_autorizador = Empleado::extraerNombresApellidos(Empleado::find($this->transferencia->autorizador_id));
        $justificacion = $this->transferencia->justificacion;
        $codigo = 'TRANSF-' . $this->transferencia->id;
        return 'El empleado ' . $nombres_emisor . ' acaba de transferir EPPS. Cód.transferencia: ' . $codigo . '. Justificación: ' . $justificacion . ' y fue autorizado por ' . $nombres_autorizador . '. **Esta notificación es sólo informativa.';
    }
}
