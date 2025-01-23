<?php

namespace App\Jobs\TrabajoSocial;

use App\Events\TrabajoSocial\NotificarActualizacionFichaSocioeconomicaEvent;
use App\Models\RecursosHumanos\TrabajoSocial\FichaSocioeconomica;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Throwable;

class NotificarActualizacionFichaSocioeconomicaJob implements ShouldQueue
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
     * @throws Throwable
     */
    public function handle()
    {
        $fecha = Carbon::now();
        $ficha_socioeconomicas = FichaSocioeconomica::where('updated_at', '<', $fecha->subYear())->get();

        foreach ($ficha_socioeconomicas as $ficha) {
            event(new NotificarActualizacionFichaSocioeconomicaEvent($ficha));
        }
    }
}
