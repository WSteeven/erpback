<?php

namespace App\Jobs;

use App\Events\RecursosHumanos\VacacionNotificacionEvent;
use App\Models\Empleado;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class NotificarVacacionesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $empleados = Empleado::selectRaw("id,nombres,apellidos,TIMESTAMPDIFF(MONTH,STR_TO_DATE(fecha_ingreso, '%d-%m-%Y'), NOW()) % 12 as diffMonths,
            DATEDIFF(NOW(), STR_TO_DATE(fecha_ingreso, '%d-%m-%Y')) % 30 as diffDays")
                ->whereRaw("DATEDIFF(NOW(), STR_TO_DATE(fecha_ingreso, '%d-%m-%Y')) % 30 = 14")
                ->having('diffMonths', '=', 0)
                ->get();
            foreach ($empleados as $empleado) {
                event(new VacacionNotificacionEvent($empleado));
            }
        } catch (Exception $e) {
            Log::channel('testing')->info('Log', ['error', $e->getMessage(), $e->getLine()]);
        }
    }
}
