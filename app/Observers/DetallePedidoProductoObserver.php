<?php

namespace App\Observers;

use App\Models\DetallePedidoProducto;
use App\Models\EstadoTransaccion;
use App\Models\Pedido;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DetallePedidoProductoObserver
{
    /**
     * Handle the DetallePedidoProducto "created" event.
     *
     * @param  \App\Models\DetallePedidoProducto  $detallePedidoProducto
     * @return void
     */
    public function created(DetallePedidoProducto $detallePedidoProducto)
    {
        Log::channel('testing')->info('Log', ['Created del observer de DetallePedidoProducto', $detallePedidoProducto]);
    }

    /**
     * Handle the DetallePedidoProducto "updated" event.
     *
     * @param  \App\Models\DetallePedidoProducto  $detallePedidoProducto
     * @return void
     */
    public function updated(DetallePedidoProducto $detallePedidoProducto)
    {
        $estadoCompleta = EstadoTransaccion::where('nombre', EstadoTransaccion::COMPLETA)->first();
        $estadoParcial = EstadoTransaccion::where('nombre', EstadoTransaccion::PARCIAL)->first();
        Log::channel('testing')->info('Log', ['Updated del observer de DetallePedidoProducto', $detallePedidoProducto]);
        // $resultados = DB::table('detalle_pedido_producto')->where('pedido_id', '=', $detallePedidoProducto->pedido_id)->whereRaw('cantidad!=despachado')->get();
        // $resultados = DetallePedidoProducto::where('pedido_id', '=', $detallePedidoProducto->pedido_id)->where('cantidad', '<>', 'despachado')->count();
        $resultados = DB::select('select count(*) as cantidad from detalle_pedido_producto dpp where dpp.pedido_id=' . $detallePedidoProducto->pedido_id . ' and dpp.cantidad!=dpp.despachado');
        // Log::channel('testing')->info('Log', ['Resultados', $resultados]);
        Log::channel('testing')->info('Log', ['Resultados', $resultados[0]->cantidad]);
        // $results = collect($resultados)->map(fn($items)=>[
        //     'cantidad'=>intval($items->cantidad),
        // ]);
        // Log::channel('testing')->info('Log', ['Results var', $results]);
        // Log::channel('testing')->info('Log', ['Results var', $results->count()]);
        // Log::channel('testing')->info('Log', ['Cantidad de resultados', $resultados[0]['cantidad']]);
        $pedido = Pedido::find($detallePedidoProducto->pedido_id);
        if ($resultados[0]->cantidad>0) {
            Log::channel('testing')->info('Log', ['todavia no esta completada']);
            $pedido->update(['estado_id' => $estadoParcial->id]);
        } else {
            Log::channel('testing')->info('Log', ['el pedido esta completada!!']);
            $pedido->update(['estado_id' => $estadoCompleta->id]);
        }
    }

    /**
     * Handle the DetallePedidoProducto "deleted" event.
     *
     * @param  \App\Models\DetallePedidoProducto  $detallePedidoProducto
     * @return void
     */
    public function deleted(DetallePedidoProducto $detallePedidoProducto)
    {
        Log::channel('testing')->info('Log', ['Deleted del observer de DetallePedidoProducto', $detallePedidoProducto]);
    }

    /**
     * Handle the DetallePedidoProducto "restored" event.
     *
     * @param  \App\Models\DetallePedidoProducto  $detallePedidoProducto
     * @return void
     */
    public function restored(DetallePedidoProducto $detallePedidoProducto)
    {
        //
    }

    /**
     * Handle the DetallePedidoProducto "force deleted" event.
     *
     * @param  \App\Models\DetallePedidoProducto  $detallePedidoProducto
     * @return void
     */
    public function forceDeleted(DetallePedidoProducto $detallePedidoProducto)
    {
        //
    }
}
