<?php

namespace App\Listeners;

use App\Events\PedidoCreadoEvent;
use App\Events\PedidoEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class PedidoListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(PedidoCreadoEvent $event)
    {
        //
    }
}
