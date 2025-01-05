<?php

namespace App\Jobs;

use App\Events\RecursosHumanos\PermisoNotificacionEvent;
use App\Models\RecursosHumanos\NominaPrestamos\PermisoEmpleado;
use Carbon\Carbon;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

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
        try {
            $permisos = PermisoEmpleado::where('estado',2)->whereDate('fecha_hora_inicio', '>=', now())
            ->whereDate('fecha_hora_inicio', '<=', now()->addDay())
            ->with('empleado_info')
            ->get();
            foreach ($permisos as $permiso) {
                event(new PermisoNotificacionEvent($permiso));
            }
        } catch (Exception $e) {
            Log::channel('testing')->info('Log', ['error', $e->getMessage(), $e->getLine()]);
        }

    }
}
