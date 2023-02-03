<?php

namespace App\Observers;

use App\Models\DetalleProductoTransaccion;
use App\Models\Inventario;
use App\Models\Motivo;
use App\Models\Pedido;
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
        Log::channel('testing')->info('Log', ['Created del observer de TransaccionBodega', $transaccionBodega]);
        /* $tipoTransaccion = TipoTransaccion::where('nombre', 'EGRESO')->first();
        $motivos = Motivo::where('tipo_transaccion_id', $tipoTransaccion->id)->get('id');
        Log::channel('testing')->info('Log', ['ids de motivos', $motivos]);
        if ($motivos->contains('id', '=', $transaccionBodega->motivo_id)) {
            Log::channel('testing')->info('Log', ['Es una transaccion de EGRESO']);
            if ($transaccionBodega->pedido_id) {
                $pedido = Pedido::find($transaccionBodega->pedido_id);
                Log::channel('testing')->info('Log', ['pedido encontrado', $pedido, 'id de transaccion:', $transaccionBodega->id]);
                $id = $transaccionBodega->id;
                if($id){
                    Log::channel('testing')->info('Log', ['El id de la transaccion es:', $id]);
                    $detallesTransaccion = DetalleProductoTransaccion::where('transaccion_id', '=', $id)->get();
                    Log::channel('testing')->info('Log', ['detalles dentro del if son:', $detallesTransaccion]);
                }
                $detallesTransaccion = DetalleProductoTransaccion::where('transaccion_id', '=', $transaccionBodega->id)->get();
                // $detallesTransaccion = TransaccionBodega::find($transaccionBodega->id)->items()->get();
                Log::channel('testing')->info('Log', ['detalles son:', $detallesTransaccion]);
                foreach ($detallesTransaccion as $detalle) {
                    Log::channel('testing')->info('Log', ['detalle en el foreach', $detalle]);
                    $item = Inventario::find($detalle['inventario_id']);
                    Log::channel('testing')->info('Log', ['item del inventario', $item]);
                }
            }
        } else {
            Log::channel('testing')->info('Log', ['Es una transaccion de INGRESO']);
        } */
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
