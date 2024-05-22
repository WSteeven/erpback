<?php

namespace App\Jobs\Vehiculos;

use App\Models\Vehiculos\BitacoraVehicular;
use App\Models\Vehiculos\MantenimientoVehiculo;
use App\Models\Vehiculos\PlanMantenimiento;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ActualizarMantenimientoVehiculoJob implements ShouldQueue
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
        //Revisamos si todos los mantenimientos pendientes han excedido el km de aplicar_cada
        $mantenimientos = MantenimientoVehiculo::where('estado', MantenimientoVehiculo::PENDIENTE)->get();
        foreach ($mantenimientos as $mantenimiento) {
            //Buscamos el plan de mantenimiento y la ultima bitacora del vehiculo para comparar los kms transcurridos
            $plan = PlanMantenimiento::where('vehiculo_id', $mantenimiento->vehiculo_id)->where('servicio_id', $mantenimiento->servicio_id)->get();
            if ($plan) {
                $bitacora = BitacoraVehicular::where('vehiculo_id', $mantenimiento->vehiculo_id)->where('firmada', true)->orderBy('id', 'desc')->first();
                $ultimoMantenimiento = MantenimientoVehiculo::where('vehiculo_id', $mantenimiento->vehiculo_id)->where('servicio_id', $mantenimiento->servicio_id)->where('estado', MantenimientoVehiculo::REALIZADO)->orderBy('id', 'desc')->first();
                if ($ultimoMantenimiento) {
                    //Se realiza el calculo en base al km_realizado del ultimo mantenimiento
                    if ($bitacora->km_final > $ultimoMantenimiento->km_realizado + $plan->aplicar_cada) {
                        $mantenimiento->estado = MantenimientoVehiculo::RETRASADO;
                        $mantenimiento->km_retraso = $bitacora->km_final - ($ultimoMantenimiento->km_realizado + $plan->aplicar_cada);
                        $mantenimiento->save();
                    }
                } else {
                    //Se realiza el calculo como que fuera el primer mantenimiento

                }
            }
        }
    }
}
