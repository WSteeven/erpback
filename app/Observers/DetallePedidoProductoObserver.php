<?php

namespace App\Observers;

use App\Events\PedidoCreadoEvent;
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

        // Log::channel('testing')->info('Log', ['Updated del observer de DetallePedidoProducto', $detallePedidoProducto]);

        DetallePedidoProducto::verificarDespachoItems($detallePedidoProducto);
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
        DetallePedidoProducto::verificarDespachoItems($detallePedidoProducto);
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
