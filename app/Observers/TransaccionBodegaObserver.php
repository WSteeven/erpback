<?php

namespace App\Observers;

use App\Models\Motivo;
use App\Models\TipoTransaccion;
use App\Models\TransaccionBodega;
use Illuminate\Support\Facades\Log;

class TransaccionBodegaObserver
{
    
    /**
     * Handle the TransaccionBodega "created" event.
     *
     * @param  \App\Models\TransaccionBodega  $transaccionBodega
     * @return void
     */
    public function created(TransaccionBodega $transaccionBodega)
    {
        $tipoTransaccion = TipoTransaccion::where('nombre', 'EGRESO')->first();
        $motivos = Motivo::where('tipo_transaccion_id', $tipoTransaccion->id)->get('id')->toArray();
        Log::channel('testing')->info('Log', ['Created del observer de TransaccionBodega', $transaccionBodega, $motivos]);
        if(in_array($transaccionBodega->motivo,$motivos)){
            Log::channel('testing')->info('Log', ['Es una transaccion de EGRESO']);
        }else{
            Log::channel('testing')->info('Log', ['Es una transaccion de INGRESO']);
        }
    }

    /**
     * Handle the TransaccionBodega "updated" event.
     *
     * @param  \App\Models\TransaccionBodega  $transaccionBodega
     * @return void
     */
    public function updated(TransaccionBodega $transaccionBodega)
    {
        //
    }

    /**
     * Handle the TransaccionBodega "deleted" event.
     *
     * @param  \App\Models\TransaccionBodega  $transaccionBodega
     * @return void
     */
    public function deleted(TransaccionBodega $transaccionBodega)
    {
        //
    }

    /**
     * Handle the TransaccionBodega "restored" event.
     *
     * @param  \App\Models\TransaccionBodega  $transaccionBodega
     * @return void
     */
    public function restored(TransaccionBodega $transaccionBodega)
    {
        //
    }

    /**
     * Handle the TransaccionBodega "force deleted" event.
     *
     * @param  \App\Models\TransaccionBodega  $transaccionBodega
     * @return void
     */
    public function forceDeleted(TransaccionBodega $transaccionBodega)
    {
        //
    }
}
