<?php

namespace App\Observers;

use App\Models\DetalleProductoTransaccion;
use App\Models\Inventario;
use App\Models\MovimientoProducto;
use App\Models\Pedido;
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

        /* MovimientoProducto::create([
            'inventario_id'=> $item->id,
            'transaccion_id'=>$request->transaccion_id,
            'cantidad'=>$request->cantidad,
            'precio_unitario'=>$item->detalle->precio_compra,
            'saldo'=>$item->cantidad-$request->cantidad
        ]); */

        //Se debe llamar al store de movimiento desde el front
        $transaccion = TransaccionBodega::findOrFail($detalleProductoTransaccion->transaccion_id);
        Log::channel('testing')->info('Log', ['Hubo transaccion?', $transaccion]);
        if ($transaccion->pedido_id) {
            $pedido = Pedido::find($transaccion->pedido_id);
            Log::channel('testing')->info('Log', ['Hubo pedido?', $pedido]);
        }
        Log::channel('testing')->info('Log', ['transaccion en el metodo updated del observer DetalleProductoTransaccionObserver', $transaccion]);
        $detallesTransaccion = DetalleProductoTransaccion::where('transaccion_id', $transaccion->id)->get();
        $esParcial = false;
        foreach ($detallesTransaccion as $detalle) {
            if ($detalle->cantidad_inicial !== $detalle->cantidad_final) {
                $esParcial = true;
            }
            // Log::channel('testing')->info('Log', ['foreach del observer DetalleProductoTransaccionObserver', $detalle, $detalle->cantidad_final]);
        }
        if ($esParcial) {
            $transaccion->estados()->attach(3);
            if ($pedido) $pedido->update(['estado_id' => 3]);
        } else {
            $transaccion->estados()->attach(2);
            if ($pedido) $pedido->update(['estado_id' => 2]);
        }
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
