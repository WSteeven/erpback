<?php

namespace App\Observers;

use App\Models\DetalleProductoTransaccion;
use App\Models\TransaccionBodega;
use Illuminate\Support\Facades\Log;

class DetalleProductoTransaccionObserver
{
    /**
     * Handle the DetalleProductoTransaccion "created" event.
     *
     * @param  \App\Models\DetalleProductoTransaccion  $detalleProductoTransaccion
     * @return void
     */
    public function created(DetalleProductoTransaccion $detalleProductoTransaccion)
    {
        Log::channel('testing')->info('Log', ['metodo created del observer DetalleProductoTransaccionObserver', $detalleProductoTransaccion]);
        
    }

    /**
     * Handle the DetalleProductoTransaccion "updated" event.
     *
     * @param  \App\Models\DetalleProductoTransaccion  $detalleProductoTransaccion
     * @return void
     */
    public function updated(DetalleProductoTransaccion $detalleProductoTransaccion)
    {
        Log::channel('testing')->info('Log', ['metodo updated del observer DetalleProductoTransaccionObserver', $detalleProductoTransaccion]);
        // $transaccion = TransaccionBodega::findOrFail($detalleProductoTransaccion->transaccion_id)->get();

    }

    /**
     * Handle the DetalleProductoTransaccion "deleted" event.
     *
     * @param  \App\Models\DetalleProductoTransaccion  $detalleProductoTransaccion
     * @return void
     */
    public function deleted(DetalleProductoTransaccion $detalleProductoTransaccion)
    {
        //
    }

    /**
     * Handle the DetalleProductoTransaccion "restored" event.
     *
     * @param  \App\Models\DetalleProductoTransaccion  $detalleProductoTransaccion
     * @return void
     */
    public function restored(DetalleProductoTransaccion $detalleProductoTransaccion)
    {
        //
    }

    /**
     * Handle the DetalleProductoTransaccion "force deleted" event.
     *
     * @param  \App\Models\DetalleProductoTransaccion  $detalleProductoTransaccion
     * @return void
     */
    public function forceDeleted(DetalleProductoTransaccion $detalleProductoTransaccion)
    {
        //
    }
}
