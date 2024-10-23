<?php

namespace App\Events\ComprasProveedores;

use App\Models\ComprasProveedores\PreordenCompra;
use App\Models\Notificacion;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Src\Config\TiposNotificaciones;

class PreordenCreadaEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $canalId;
    public Notificacion $notificacion;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(string $mensaje, $canal, string $url, PreordenCompra $preorden, bool $informativa)
    {
        $this->canalId = $canal;

        $this->notificacion = Notificacion::crearNotificacion($mensaje, $url, TiposNotificaciones::PREORDEN, null, null, $preorden, $informativa);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('preordenes-generadas-' . $this->canalId);
    }

    public function broadcastAs()
    {
        return 'preorden-event';
    }
}
