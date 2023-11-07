<?php

namespace App\Events\Bodega;

use App\Models\Notificacion;
use App\Models\TransaccionBodega;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Src\Config\TiposNotificaciones;

class IngresoPorCompraEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public TransaccionBodega $transaccion;
    public Notificacion $notificacion;
    public $url = '/transacciones-ingresos';
    public $canalId;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($transaccion, $canalId)
    {
        $this->transaccion = $transaccion;
        $this->canalId = $canalId;

        $this->notificacion = Notificacion::crearNotificacion($this->obtenerMensaje(), $this->url, TiposNotificaciones::INGRESO_MATERIALES, auth()->user()->empleado->id, null, $transaccion, true);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('ingresos-compras-proveedores-' . $this->canalId);
    }
    public function broadcastAs()
    {
        return  'ingreso-event';
    }

    public function obtenerMensaje()
    {
        return 'INGRESO [' . $this->transaccion->id . ']. ' . $this->transaccion->atiende->nombres . ' ' . $this->transaccion->atiende->apellidos . ' ha realizado un ingreso de materiales con motivo ' . $this->transaccion->motivo->nombre . ' en la sucursal ' . $this->transaccion->sucursal->lugar;
    }
}
