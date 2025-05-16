<?php

namespace App\Events\Bodega;

use App\Models\Notificacion;
use App\Models\TransaccionBodega;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Src\Config\TiposNotificaciones;
use Throwable;

class TransaccionEgresoEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;


    public Notificacion $notificacion;
    public TransaccionBodega $transaccion;

    /**
     * Create a new event instance.
     *
     * @return void
     * @throws Throwable
     */
    public function __construct($mensaje, $url, $transaccion, $informativa)
    {
        $this->transaccion = $transaccion;
        $this->notificacion = Notificacion::crearNotificacion($mensaje, $url, TiposNotificaciones::EGRESO, $transaccion->solicitante_id, $transaccion->responsable_id, $transaccion, $informativa);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel
     */
    public function broadcastOn()
    {
        return new Channel('egreso-' . $this->transaccion->responsable_id);
    }
    public function broadcastAs()
    {
        return 'egreso-event';
    }
}
