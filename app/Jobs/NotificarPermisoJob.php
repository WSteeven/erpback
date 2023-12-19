<?php

namespace App\Jobs;

use App\Events\PermisoNotificacionEvent;
use App\Models\RecursosHumanos\NominaPrestamos\PermisoEmpleado;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class NotificarPermisoJob implements ShouldQueue
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
        $permisos = PermisoEmpleado::where('estado',2)->whereDate('fecha_hora_inicio', '>=', now())
        ->whereDate('fecha_hora_inicio', '<=', now()->addDay())
        ->with('empleado_info')
        ->get();
        foreach ($permisos as $permiso) {
            event(new PermisoNotificacionEvent($permiso));
        }

    }
}
