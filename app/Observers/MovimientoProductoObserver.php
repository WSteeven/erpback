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
        Log::channel('testing')->info('Log', ['item del inventario es', $itemInventario]);
        $itemDetalleProductoTransaccion = DetalleProductoTransaccion::findOrFail($movimientoProducto->detalle_producto_transaccion_id);
        Log::channel('testing')->info('Log', ['DetalleProductoTransaccion es', $itemDetalleProductoTransaccion]);
        $transaccion = TransaccionBodega::findOrFail($itemDetalleProductoTransaccion->transaccion_id);
        $detalleProductos = [];
        if ($itemInventario) {
            if ($itemDetalleProductoTransaccion) {
                $itemDetalleProductoTransaccion->update([
                    'cantidad_final' => $movimientoProducto->cantidad
                ]);
                Log::channel('testing')->info('Log', ['DetalleProductoTransaccion actualizado']);
                $itemInventario->update([
                    'cantidad'=>$itemInventario->cantidad-$movimientoProducto->cantidad
                ]);
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
