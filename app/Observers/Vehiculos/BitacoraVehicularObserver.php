<?php

namespace App\Observers\Vehiculos;

use App\Events\Vehiculos\NotificarBajoNivelCombustible;
use App\Models\User;
use App\Models\Vehiculos\BitacoraVehicular;
use Illuminate\Support\Facades\Log;
use Src\App\EmpleadoService;

class BitacoraVehicularObserver
{
    /**
     * Handle the BitacoraVehicular "created" event.
     *
     * @param  \App\Models\Vehiculos\BitacoraVehicular  $bitacora
     * @return void
     */
    public function created(BitacoraVehicular $bitacora)
    {
        //
    }

    /**
     * Handle the BitacoraVehicular "updated" event.
     *
     * @param  \App\Models\Vehiculos\BitacoraVehicular  $bitacora
     * @return void
     */
    public function updated(BitacoraVehicular $bitacora)
    {
        // if ($bitacora->firmada) {
            //Lanzar notificacion de advertencia de combustible
            if ($bitacora->tanque_final < 50) {
                $admin_vehiculos = EmpleadoService::obtenerEmpleadoRolEspecifico(User::ROL_ADMINISTRADOR_VEHICULOS, true);
                event(new NotificarBajoNivelCombustible($bitacora, $admin_vehiculos->id));
            }

            //Aquí se revisa si 
            Log::channel('testing')->info('Log', ['accesorios', $bitacora->checklistAccesoriosVehiculo]);
            Log::channel('testing')->info('Log', ['vehiculo', $bitacora->checklistVehiculo()->orderBy('id', 'desc')->first()]);
            Log::channel('testing')->info('Log', ['imagenes', $bitacora->checklistImagenVehiculo()->orderBy('id', 'desc')->first()]);
        // }
    }

    /**
     * Handle the BitacoraVehicular "deleted" event.
     *
     * @param  \App\Models\Vehiculos\BitacoraVehicular  $bitacora
     * @return void
     */
    public function deleted(BitacoraVehicular $bitacora)
    {
        //
    }

    /**
     * Handle the BitacoraVehicular "restored" event.
     *
     * @param  \App\Models\Vehiculos\BitacoraVehicular  $bitacora
     * @return void
     */
    public function restored(BitacoraVehicular $bitacora)
    {
        //
    }

    /**
     * Handle the BitacoraVehicular "force deleted" event.
     *
     * @param  \App\Models\Vehiculos\BitacoraVehicular  $bitacora
     * @return void
     */
    public function forceDeleted(BitacoraVehicular $bitacora)
    {
        //
    }
}
