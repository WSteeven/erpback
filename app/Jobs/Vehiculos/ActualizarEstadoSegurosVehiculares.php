<?php

namespace App\Jobs\Vehiculos;

use App\Events\RecursosHumanos\Vehiculos\NotificarSeguroVencidoEvent;
use App\Models\Vehiculos\SeguroVehicular;
use App\Models\Vehiculos\Vehiculo;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

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
     */
    public function handle()
    {
        $fecha_actual = Carbon::now();
        //Se verifica si una poliza de seguro está vencida y le cambia el estado a desactivado
        $seguros = SeguroVehicular::where('estado', true)
            ->where('fecha_caducidad', '<', $fecha_actual->format('Y-m-d'))->get();
        foreach ($seguros as $seguro) {
            $seguro->update(['estado', false]);
            $seguro->save();
            $vehiculo = Vehiculo::where('seguro_id', $seguro->id)->get(); //Si hay un vehiculo con un seguro caducado asignado se notifica para que cambie de seguro el vehículo
            if ($vehiculo) {
                event(new NotificarSeguroVencidoEvent($seguro, 'vencido'));
                $vehiculo->seguro_id = null;
                $vehiculo->save();
            }
        }

        //A partir de 15 días antes del vencimiento lanzará una notificación de proximo a vencer
        $seguros_por_vencer =  SeguroVehicular::where('estado', true)
            ->where('fecha_caducidad', '<=', ($fecha_actual->addDays(15))->format('Y-m-d'))->get();
        foreach ($seguros_por_vencer as $seguro) {
            event(new NotificarSeguroVencidoEvent($seguro, 'por_vencer'));
        }
    }
}
