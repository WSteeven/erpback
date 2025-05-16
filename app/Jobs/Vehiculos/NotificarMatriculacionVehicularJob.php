<?php

namespace App\Jobs\Vehiculos;

use App\Events\Vehiculos\NotificarMatriculacionVehicularEvent;
use App\Models\Vehiculos\Matricula;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;

class NotificarMatriculacionVehicularJob implements ShouldQueue
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
        try {
            $anio_actual = Carbon::now()->year;
            $mes_actual = Carbon::now()->month;
            $matriculas_mes = Matricula::where('matriculado', false)->whereMonth('fecha_matricula', $mes_actual)->get();
            foreach ($matriculas_mes as $matricula) {
                event(new NotificarMatriculacionVehicularEvent($matricula, 'mes'));
            }
            $matriculas_vencidas = Matricula::where('matriculado', false)->whereYear('fecha_matricula', $anio_actual)->whereMonth('fecha_matricula', '<', $mes_actual)->get();
            foreach ($matriculas_vencidas as $matricula) {
                event(new NotificarMatriculacionVehicularEvent($matricula, 'vencidas'));
            }
        } catch (Throwable $th) {
            Log::channel('testing')->info('Log', ['[JOB ERROR][NotificarMatriculacionVehicularJob]', $th->getMessage(), $th->getLine()]);
        }
    }
}
