<?php

namespace App\Observers;

use App\Models\DetalleProducto;
use App\Models\DetalleProductoTransaccion;
use App\Models\Inventario;
use App\Models\MovimientoProducto;
use App\Models\TransaccionBodega;
use Illuminate\Support\Facades\Log;

class MovimientoProductoObserver
{
    /**
     * Handle the MovimientoProducto "created" event.
     *
     * @param  \App\Models\MovimientoProducto  $movimientoProducto
     * @return void
     */
    public function created(MovimientoProducto $movimientoProducto)
    {
        Log::channel('testing')->info('Log', ['Observer de created del movimiento', $movimientoProducto]);
        $itemInventario = Inventario::findOrFail($movimientoProducto->inventario_id);
        $transaccion = TransaccionBodega::findOrFail($movimientoProducto->transaccion_id);
        $detalleProductos=[];
        if($itemInventario){
            $detalleProductos = DetalleProductoTransaccion::where('transaccion_id', $movimientoProducto->transaccion_id)->where('detalle_id', $itemInventario->detalle_id)->get();
            if($detalleProductos){
                foreach($detalleProductos as $detalleProducto){
                    $detalleProducto->update([
                        'cantidad_final'=>$movimientoProducto->cantidad
                    ]);
                }
                $transaccion->estados()->attach(2);
            }
        }

    }

    /**
     * Handle the MovimientoProducto "updated" event.
     *
     * @param  \App\Models\MovimientoProducto  $movimientoProducto
     * @return void
     */
    public function updated(MovimientoProducto $movimientoProducto)
    {
        //
    }

    /**
     * Handle the MovimientoProducto "deleted" event.
     *
     * @param  \App\Models\MovimientoProducto  $movimientoProducto
     * @return void
     */
    public function deleted(MovimientoProducto $movimientoProducto)
    {
        //
    }

    /**
     * Handle the MovimientoProducto "restored" event.
     *
     * @param  \App\Models\MovimientoProducto  $movimientoProducto
     * @return void
     */
    public function restored(MovimientoProducto $movimientoProducto)
    {
        //
    }

    /**
     * Handle the MovimientoProducto "force deleted" event.
     *
     * @param  \App\Models\MovimientoProducto  $movimientoProducto
     * @return void
     */
    public function forceDeleted(MovimientoProducto $movimientoProducto)
    {
        //
    }
}
