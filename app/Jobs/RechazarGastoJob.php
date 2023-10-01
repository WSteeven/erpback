<?php

namespace App\Jobs;

use App\Events\FondoRotativoEvent;
use App\Models\FondosRotativos\Gasto\Gasto;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class RechazarGastoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $gasto_pendientes = Gasto::where('estado', 3)->get();
        foreach ($gasto_pendientes as $gasto) {
            $gasto->estado =2;
            event(new FondoRotativoEvent($gasto));
        }
        $gasto = Gasto::where('estado', 3)->update(array('estado' => 2, 'detalle_estado' => 'RECHAZADO POR EL SISTEMA'));
    }
}
