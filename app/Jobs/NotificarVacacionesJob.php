<?php

namespace App\Jobs;

use App\Events\VacacionNotificacionEvent;
use App\Models\Empleado;
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
        $empleados = Empleado::selectRaw("id,nombres,apellidos,TIMESTAMPDIFF(MONTH,STR_TO_DATE(fecha_ingreso, '%d-%m-%Y'), NOW()) % 12 as diffMonths,
        DATEDIFF(NOW(), STR_TO_DATE(fecha_ingreso, '%d-%m-%Y')) % 30 as diffDays")
            ->whereRaw("DATEDIFF(NOW(), STR_TO_DATE(fecha_ingreso, '%d-%m-%Y')) % 30 = 14")
            ->having('diffMonths', '=', 11)
            ->get();
        foreach ($empleados as $empleado) {
            event(new VacacionNotificacionEvent($empleado));
        }
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
    }
}
