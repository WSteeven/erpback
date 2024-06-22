<?php

namespace App\Jobs\Vehiculos;

use App\Models\Vehiculos\Matricula;
use App\Models\Vehiculos\Vehiculo;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Src\Shared\Utils;

class CrearMatriculasAnualesVehiculosJob implements ShouldQueue
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
        // el primer día de enero de cada año se crearán registros de matriculas para el año en curso para todos los vehiculos registrados
        $vehiculos = Vehiculo::where('estado', true)->get();
        foreach ($vehiculos as $vehiculo) {
            $fecha = Carbon::now();
            $anio_actual = $fecha->year;
            $fecha->day(1); //seteamos el primer día del mes
            $tieneMaticulaAnioActual = !!$vehiculo->matriculas()->whereYear('fecha_matricula', $anio_actual)->first();
            if (!$tieneMaticulaAnioActual) {
                //Si no tiene matricula de este año se creará una nueva   
                $fecha->month(Utils::obtenerMesMatricula(Utils::obtenerUltimoDigito($vehiculo->placa)));
                Matricula::crearMatricula($vehiculo->id, $fecha->toDateString(), $fecha->addYear()->toDateString());
            }
                // $matricula = Matricula::crearMatricula($vehiculo->id, $fecha->toDateString(), $fecha->addYear()->toDateString());
                // Log::channel('testing')->info('Log', ['matricula creada', $matricula]);
            // } else {
                // Log::channel('testing')->info('Log', ['Vehiculo que tiene matricula de este año', $vehiculo]);
            // }
        }
    }
}
