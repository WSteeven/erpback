<?php

namespace App\Jobs\RecursosHumanos;

use App\Models\Empleado;
use App\Models\RecursosHumanos\NominaPrestamos\Periodo;
use App\Models\RecursosHumanos\NominaPrestamos\Vacacion;
use Carbon\Carbon;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Src\App\RecursosHumanos\NominaPrestamos\VacacionService;
use Throwable;

class CrearVacacionesEmpleadoJob implements ShouldQueue
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
            $this->crearPeriodosFaltantes();
            // verificamos si hay algun empleado que ya tenga un año de antiguedad
            // el empleado debe estar activo y si tiene más de 1 año de antiguedad
            // deben crearse n registros conforme a los años de antiguedad del empleado
            $empleados = Empleado::where('estado', true)->where('fecha_ingreso', '<=', Carbon::now()->subYear())->get();
            foreach ($empleados as $empleado) {
                // Aqui vamos a verificar si el empleado tiene más de un año de antiguedad, en caso de que tenga 2 o más años deben crearse las respectivas vacaciones para esos años y periodos
                $fecha_ingreso = Carbon::parse($empleado->fecha_ingreso);
                $anio_ingreso = $fecha_ingreso->year;
                $anio_actual = Carbon::now()->year;

                for ($anio = $anio_ingreso; $anio <= $anio_actual; $anio++) {
                    $nombre_periodo = $anio . '-' . ($anio + 1);

                    $periodo = Periodo::where('nombre', $nombre_periodo)->first();

                    $fecha_limite_vacaciones = $fecha_ingreso->copy()->addYears($anio-$anio_ingreso+1)->subDay();

                    if(Carbon::now()->lessThan($fecha_limite_vacaciones)) continue;

                    $existe_vacacion = Vacacion::where('empleado_id', $empleado->id)->where('periodo_id', $periodo->id)->exists();
                    if (!$existe_vacacion) {
                        // Se crea la vacacion en caso de que no exista, en este caso segun la antiguedad se crearán varias vacaciones o no
                        VacacionService::crearVacacion($empleado->id, $periodo->id);
                    }
                }
            }
        } catch (Throwable $ex) {
            Log::channel('testing')->error('ERROR', ['Error en crear vacaciones JOB', $ex->getMessage(), $ex->getLine()]);
        }

    }

    /**
     * Realiza una busqueda desde el año más antiguo según la fecha de ingreso del empleado más antiguo,
     * y crea los periodos faltantes en caso de no haber
     * @return void
     */
    private function crearPeriodosFaltantes()
    {
        try {
            $fecha_ingreso_mas_antigua = Carbon::parse(Empleado::where('estado', true)->whereNotNull('fecha_ingreso')->orderBy('fecha_ingreso')->first()->fecha_ingreso);
            $anio_actual = Carbon::now()->year;

            for ($anio = $fecha_ingreso_mas_antigua->year; $anio <= $anio_actual; $anio++) {
                $nombre_periodo = $anio . '-' . ($anio + 1);
                $existe_periodo = Periodo::where('nombre', $nombre_periodo)->exists();
                if (!$existe_periodo) {
                    Periodo::create([
                        'nombre' => $nombre_periodo,
                        'activo' => true,
                    ]);
                }
            }
        } catch (Exception $ex) {
            Log::channel('testing')->error('ERROR', ['Error en crear periodos faltantes', $ex->getMessage(), $ex->getLine()]);
        }
    }
}
