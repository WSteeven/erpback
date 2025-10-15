<?php

namespace App\Jobs\Vehiculos;

use App\Events\Vehiculos\NotificarSeguroVencidoEvent;
use App\Models\Vehiculos\SeguroVehicular;
use App\Models\Vehiculos\Vehiculo;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Throwable;

class ActualizarEstadoSegurosVehiculares implements ShouldQueue
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
        $fechaActual = Carbon::now();
        $fechaLimite = Carbon::now()->addDays(15);
        //Se verifica si una poliza de seguro está vencida y le cambia el estado a desactivado
        $seguros = SeguroVehicular::where('estado', true)
            ->where('fecha_caducidad', '<', $fechaActual->format('Y-m-d'))->get();
        foreach ($seguros as $seguro) {
            $seguro->update(['estado' => false]);

            $vehiculo = Vehiculo::where('seguro_id', $seguro->id)->first(); //Si hay un vehiculo con un seguro caducado asignado se notifica para que cambie de seguro el vehículo
            if ($vehiculo) {
                event(new NotificarSeguroVencidoEvent($seguro, 'vencido'));
                $vehiculo->seguro_id = null;
                $vehiculo->save();
            }
        }

        //A partir de 15 días antes del vencimiento lanzará una notificación de proximo a vencer
        $seguros_por_vencer = SeguroVehicular::where('estado', true)
            ->where('fecha_caducidad', '<=', $fechaLimite->format('Y-m-d'))->get();
        if ($seguros_por_vencer->count() > 0)
            foreach ($seguros_por_vencer as $seguro) {
                event(new NotificarSeguroVencidoEvent($seguro, 'por_vencer'));
            }
    }
}
