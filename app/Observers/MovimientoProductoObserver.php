<?php

namespace App\Observers;

use App\Models\DetalleProducto;
use App\Models\DetalleProductoTransaccion;
use App\Models\Inventario;
use App\Models\MaterialGrupoTarea;
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
        // $itemInventario = Inventario::findOrFail($movimientoProducto->inventario_id);
        // // Log::channel('testing')->info('Log', ['item del inventario es', $itemInventario]);
        // $itemDetalleProductoTransaccion = DetalleProductoTransaccion::findOrFail($movimientoProducto->detalle_producto_transaccion_id);
        // // Log::channel('testing')->info('Log', ['DetalleProductoTransaccion es', $itemDetalleProductoTransaccion]);
        // $transaccion = TransaccionBodega::findOrFail($itemDetalleProductoTransaccion->transaccion_id);
        // $detalleProductos = [];
        // if ($itemInventario) {
        //     if ($itemDetalleProductoTransaccion) {
        //         $itemDetalleProductoTransaccion->update([
        //             'cantidad_final' => $movimientoProducto->cantidad
        //         ]);
                // Log::channel('testing')->info('Log', ['DetalleProductoTransaccion actualizado']);
                /* $itemInventario->update([
                    'cantidad'=>$itemInventario->cantidad-$movimientoProducto->cantidad
                ]); */

                // Cuando un material es despachado para una tarea éste se agrega a la tabla de de control de materiales para tarea en donde se asigna a un grupo
                /* $tarea_id = $transaccion->tarea_id;
                $grupo_id = $transaccion->solicitante->grupo_id;

                if ($tarea_id) {
                    $detalle_existente = MaterialGrupoTarea::where('detalle_producto_id', $itemDetalleProductoTransaccion->detalle_id)->where('tarea_id', $tarea_id)->where('grupo_id', $grupo_id)->first();
                    if (!$detalle_existente) {
                        MaterialGrupoTarea::create([
                            'detalle_tarea_id' => $detalle_existente->detalle_producto_id,
                            'cantidad_stock' => $movimientoProducto->cantidad,
                            'tarea_id' => $tarea_id,
                            'grupo_id' => $transaccion->solicitante_id,
                        ]);
                    } else {
                        $detalle_existente->update([
                            'detalle_tarea_id' => $detalle_existente->detalle_producto_id,
                            'cantidad_stock' => $detalle_existente->cantidad_stock + $movimientoProducto->cantidad,
                            'tarea_id' => $tarea_id,
                            'grupo_id' => $transaccion->solicitante_id,
                        ]);
                    }
                } */
            // }
        // }

        // Esto se recibe
        // {"inventario_id":1,"detalle_producto_transaccion_id":2,"precio_unitario":null,"tipo":"EGRESO"}
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
