<?php

namespace App\Observers;

use App\Models\Percha;
use App\Models\Piso;
use App\Models\Ubicacion;
use Illuminate\Support\Facades\Log;

class PisoObserver
{
    /**
     * Handle the Piso "created" event.
     *
     * @param  \App\Models\Piso  $piso
     * @return void
     */
    public function created(Piso $piso)
    {
        Log::channel('testing')->info('Log', ['Piso en el observer', $piso]);
        $perchas = Percha::all();
        foreach ($perchas as $percha) {
            Log::channel('testing')->info('Log', ['Percha en el observer', $piso->columna]);
            if (!is_null($piso->columna)){
                if (strlen($percha->nombre) < 5) {
                    Ubicacion::create([
                        'codigo' => Ubicacion::obtenerCodigoUbicacionPerchaPiso($percha->id, $piso->id),
                        'percha_id' => $percha->id,
                        'piso_id' => $piso->id,
                    ]);
                }
            } else {
                Ubicacion::create([
                    'codigo' => Ubicacion::obtenerCodigoUbicacionPercha($percha->id),
                    'percha_id' => $percha->id,
                ]);
            }
        }
    }

    /**
     * Handle the Piso "updated" event.
     *
     * @param  \App\Models\Piso  $piso
     * @return void
     */
    public function updated(Piso $piso)
    {
        //
    }

    /**
     * Handle the Piso "deleted" event.
     *
     * @param  \App\Models\Piso  $piso
     * @return void
     */
    public function deleted(Piso $piso)
    {
        //
    }

    /**
     * Handle the Piso "restored" event.
     *
     * @param  \App\Models\Piso  $piso
     * @return void
     */
    public function restored(Piso $piso)
    {
        //
    }

    /**
     * Handle the Piso "force deleted" event.
     *
     * @param  \App\Models\Piso  $piso
     * @return void
     */
    public function forceDeleted(Piso $piso)
    {
        //
    }
}
