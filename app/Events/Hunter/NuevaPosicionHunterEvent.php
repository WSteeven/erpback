<?php

namespace App\Events\Hunter;

use App\Models\Hunter\PosicionHunter;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * TODO: Evento para notificar nuevas posiciones de Hunter
 * TODO: Ya no se va a usar este evento, se deja por ser innecesario con actualizaciones cada 30 segundos
 */
class NuevaPosicionHunterEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $posicion;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(PosicionHunter $posicion)
    {
        $this->posicion = PosicionHunter::mapearCoordenadas($posicion);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel
     */
    public function broadcastOn()
    {
        return new Channel('posiciones-hunter');
    }
    public function broadcastAs()
    {
        return 'posicion-hunter-event';
    }
}
