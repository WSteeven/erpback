<?php

namespace App\Events\RecursosHumanos\ActivosFijos;

use App\Models\Empleado;
use App\Models\Notificacion;
use App\Models\TransaccionBodega;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Src\Config\PusherEvents;
use Src\Config\TiposNotificaciones;

class NotificarEntregaActivoFijoEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Notificacion $notificacion;
    public TransaccionBodega $transaccion_bodega;
    public int $destinatario_id;
    public string $descripcion_detalle_producto;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(TransaccionBodega $transaccion_bodega, int $destinatario_id, string $descripcion_detalle_producto)
    {
        $this->transaccion_bodega = $transaccion_bodega;
        $this->destinatario_id = $destinatario_id;
        $this->descripcion_detalle_producto = $descripcion_detalle_producto;

        $path = '/';
        $emisor_id = $transaccion_bodega->responsable_id;

        $this->notificacion = Notificacion::crearNotificacion($this->getMessage(), $path, TiposNotificaciones::ENTREGA_ACTIVO_FIJO, $emisor_id, $destinatario_id, $transaccion_bodega, true);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel(PusherEvents::ENTREGA_ACTIVO_FIJO->value . '-tracker-' . $this->destinatario_id);
    }

    public function broadcastAs()
    {
        return PusherEvents::ENTREGA_ACTIVO_FIJO->value . '-event';
    }

    private function getMessage()
    {
        $responsable = Empleado::extraerNombresApellidos($this->transaccion_bodega->responsable);
        $autorizador = Empleado::extraerNombresApellidos($this->transaccion_bodega->autoriza);
        return 'El activo fijo ' . $this->descripcion_detalle_producto . ' ha sido entregado a ' . $responsable . ' autorizado por ' . $autorizador . '. Transaccion de egreso N° ' . $this->transaccion_bodega->id;
    }
}
