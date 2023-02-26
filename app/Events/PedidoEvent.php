<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class PedidoEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $mensaje;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(string $mensaje)
    {
        $this->mensaje = $mensaje;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        // return new PrivateChannel('pedidos-tracker');
        Log::channel('testing')->info('Log', ['Estamos en el broadcastOn de pedidos']);
        return new Channel('pedidos-tracker');
    }

    public function broadcastAs(){
        Log::channel('testing')->info('Log', ['Estamos en el broadcastAs de pedidos']);
        return 'pedido-event';
    }
}
