<?php

namespace App\Jobs;

use App\Models\Tarea;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class FinalizarTareasReactivadasJob implements ShouldQueue
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
        // Actualizar las tareas que cumplen las condiciones
        Tarea::where('finalizado', 0)
            ->whereNotNull('fecha_hora_finalizacion')
            ->whereRaw('DATE_ADD(updated_at, INTERVAL 24 HOUR) <= NOW()')
            ->update(['finalizado' => 1]);
    }
}
