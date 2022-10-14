<?php

namespace App\Observers;

use App\Models\Percha;
use App\Models\Piso;
use App\Models\Ubicacion;
use Illuminate\Support\Facades\Log;

class PerchaObserver
{
    /**
     * Handle the Percha "created" event.
     *
     * @param  \App\Models\Percha  $percha
     * @return void
     */
    public function created(Percha $percha)
    {
        if(strlen($percha->nombre)<5){
            $pisos = Piso::all();
            foreach($pisos as $piso){
                if($piso->columna){
                    Ubicacion::create([
                        'codigo'=>Ubicacion::obtenerCodigoUbicacionPerchaPiso($percha->id, $piso->id),
                        'percha_id'=>$percha->id,
                        'piso_id'=>$piso->id
                    ]);
                    // Log::channel('testing')->info('Log', ['Ubicacion creada, piso actual', $piso]);
                }
            }
        }else{
            //Log::channel('testing')->info('Log', ['entro en el else', $percha]);
            Ubicacion::create([
                'codigo'=>Ubicacion::obtenerCodigoUbicacionPercha($percha->id),
                'percha_id'=>$percha->id,
            ]);
        }
        
    }

    /**
     * Handle the Percha "updated" event.
     *
     * @param  \App\Models\Percha  $percha
     * @return void
     */
    public function updated(Percha $percha)
    {
        //
    }

    /**
     * Handle the Percha "deleted" event.
     *
     * @param  \App\Models\Percha  $percha
     * @return void
     */
    public function deleted(Percha $percha)
    {
        //
    }

    /**
     * Handle the Percha "restored" event.
     *
     * @param  \App\Models\Percha  $percha
     * @return void
     */
    public function restored(Percha $percha)
    {
        //
    }

    /**
     * Handle the Percha "force deleted" event.
     *
     * @param  \App\Models\Percha  $percha
     * @return void
     */
    public function forceDeleted(Percha $percha)
    {
        //
    }
}
