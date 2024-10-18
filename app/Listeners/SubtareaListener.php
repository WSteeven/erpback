<?php

namespace App\Listeners;

use App\Events\RecursosHumanos\SubtareaEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SubtareaListener
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
     * @param  \App\Events\RecursosHumanos\SubtareaEvent  $event
     * @return void
     */
    public function handle(SubtareaEvent $event)
    {
        //
    }
}
