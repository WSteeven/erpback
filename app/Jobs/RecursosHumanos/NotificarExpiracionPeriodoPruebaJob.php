<?php

namespace App\Jobs\RecursosHumanos;

use App\Events\RecursosHumanos\NotificarExpiracionPeriodoPruebaJefeInmediatoEvent;
use App\Events\RecursosHumanos\NotificarExpiracionPeriodoPruebaRecursosHumanosEvent;
use App\Models\Empleado;
use App\Models\RecursosHumanos\Capacitacion\EvaluacionDesempeno;
use Carbon\Carbon;
use Carbon\Exceptions\Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Esto sirve para notificar al jefe inmediato de un empleado nuevo que
 * el periodo de prueba está cerca de finalizar, para que este realice
 * la evaluación de desempeño y determinar si se continua con el trabajo
 * de ese empleado o se finaliza antes de los 3 meses.
 */
class NotificarExpiracionPeriodoPruebaJob implements ShouldQueue
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
        $empleados_nuevos = Empleado::where('estado', true)->whereBetween('fecha_ingreso',[Carbon::now()->subDays(90), Carbon::now()->subDays(80)])->get();
//        Log::channel('testing')->info('Log', ['Empleados nuevos son: ', $empleados_nuevos]);

        foreach ($empleados_nuevos as $empleado) {
            $tiene_evaluacion_desempeno = EvaluacionDesempeno::where('evaluado_id', $empleado->id)->exists();
            $dias_transcurridos = Carbon::parse($empleado->fecha_ingreso)->diffInDays(Carbon::now());
//            Log::channel('testing')->info('Log', ['Empleado-dias: ', Empleado::extraerNombresApellidos($empleado), $dias_transcurridos]);
//            Log::channel('testing')->info('Log', ['Tiene evaluacion de desempeño: ', $tiene_evaluacion_desempeno]);
            if($dias_transcurridos>79 && !$tiene_evaluacion_desempeno){
                event(new NotificarExpiracionPeriodoPruebaJefeInmediatoEvent($empleado, $dias_transcurridos));
            }
            if($dias_transcurridos>84 && !$tiene_evaluacion_desempeno){
                event(new NotificarExpiracionPeriodoPruebaRecursosHumanosEvent($empleado, $dias_transcurridos));
            }
        }
    }
}
