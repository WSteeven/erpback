<?php

namespace App\Jobs\RecursosHumanos;

use App\Events\RecursosHumanos\NotificarVacacionesPlanificadasJefeInmediato;
use App\Events\RecursosHumanos\NotificarVacacionesPlanificadasRecursosHumanos;
use App\Models\RecursosHumanos\NominaPrestamos\PlanVacacion;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class NotificarPotencialesVacacionesEmpleadoJob implements ShouldQueue
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
        $tomorrow = Carbon::now()->addDay()->format('Y-m-d');
        $planes = PlanVacacion::where('fecha_inicio', $tomorrow)
            ->orWhere('fecha_inicio_primer_rango',$tomorrow )
            ->orWhere('fecha_inicio_segundo_rango',$tomorrow )->get();
        foreach ($planes as $plan) {
            event(new NotificarVacacionesPlanificadasJefeInmediato($plan));
            event(new NotificarVacacionesPlanificadasRecursosHumanos($plan));
        }


    }
}
