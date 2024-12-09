<?php

namespace App\Jobs\RecursosHumanos;

use App\Models\RecursosHumanos\EmpleadoDelegado;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class DesactivarEmpleadoDelegadoJob implements ShouldQueue
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
        $delegaciones = EmpleadoDelegado::where('activo', true)->where('fecha_hora_hasta', '<=', Carbon::now())->get();
        foreach ($delegaciones as $delegacion) {
            $delegacion->activo = false;
            $delegacion->save();
        }
    }
}
