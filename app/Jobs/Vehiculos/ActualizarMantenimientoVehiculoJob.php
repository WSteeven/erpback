<?php

namespace App\Jobs\Vehiculos;

use App\Events\RecursosHumanos\Vehiculos\NotificarMantenimientoPendienteRetrasadoEvent;
use App\Models\Vehiculos\BitacoraVehicular;
use App\Models\Vehiculos\MantenimientoVehiculo;
use App\Models\Vehiculos\PlanMantenimiento;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Src\App\Vehiculos\MantenimientoVehiculoService;
use Src\Shared\Utils;

class ActualizarMantenimientoVehiculoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private $mantenimientoService;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->mantenimientoService = new MantenimientoVehiculoService();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            //code...
            //Revisamos si todos los mantenimientos pendientes han excedido el km de aplicar_cada
            $mantenimientos = MantenimientoVehiculo::where('estado', MantenimientoVehiculo::PENDIENTE)->get();
            foreach ($mantenimientos as $mantenimiento) {
                //Buscamos el plan de mantenimiento y la ultima bitacora del vehiculo para comparar los kms transcurridos
                $itemPlan = PlanMantenimiento::where('vehiculo_id', $mantenimiento->vehiculo_id)->where('servicio_id', $mantenimiento->servicio_id)->first();
                if ($itemPlan) {
                    $bitacora = BitacoraVehicular::where('vehiculo_id', $mantenimiento->vehiculo_id)->where('firmada', true)->orderBy('id', 'desc')->first();
                    $this->mantenimientoService->actualizarMantenimiento($bitacora, $itemPlan, $mantenimiento);
                }
            }

            //Ahora que se actualizaron los mantenimientos, se notificará al admin de vehiculos
            $mantenimientos = MantenimientoVehiculo::whereIn('estado', [MantenimientoVehiculo::PENDIENTE, MantenimientoVehiculo::RETRASADO])->get();
            foreach ($mantenimientos as $mantenimiento) {
                //Aqui se notifica diariamente los mantenimientos pendientes y retrasados para cada vehículo
                // Primero se marca como leída la notificación anterior
                $mantenimiento->latestNotificacion()->update(['leida' => true]);
                event(new NotificarMantenimientoPendienteRetrasadoEvent($mantenimiento));
            }
        } catch (\Throwable $th) {
            Log::channel('testing')->info('Log', ['Error' . Utils::obtenerMensajeError($th)]);
        }
    }
}
