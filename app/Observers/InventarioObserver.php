<?php

namespace App\Observers;

use App\Models\ControlStock;
use App\Models\Inventario;

class InventarioObserver
{
    /**
     * Handle the Inventario "created" event.
     *
     * @param Inventario $inventario
     * @return void
     */
    public function created(Inventario $inventario)
    {
        // Log::channel('testing')->info('Log', ['created InventarioObserver', $inventario]);
        ControlStock::actualizarEstado($inventario->detalle_id,$inventario->sucursal_id, $inventario->cliente_id); //se actualiza el estado de control de stock

    }

    /**
     * Handle the Inventario "updated" event.
     *
     * @param Inventario $inventario
     * @return void
     */
    public function updated(Inventario $inventario)
    {
        // Log::channel('testing')->info('Log', ['updated InventarioObserver', $inventario]);
        ControlStock::actualizarEstado($inventario->detalle_id,$inventario->sucursal_id, $inventario->cliente_id); //se actualiza el estado de control de stock

    }

    /**
     * Handle the Inventario "deleted" event.
     *
     * @param Inventario $inventario
     * @return void
     */
    public function deleted(Inventario $inventario)
    {
        ControlStock::actualizarEstado($inventario->detalle_id, $inventario->sucursal_id, $inventario->cliente_id);

    }

    /**
     * Handle the Inventario "restored" event.
     *
     * @param Inventario $inventario
     * @return void
     */
    public function restored(Inventario $inventario)
    {
        //
    }

    /**
     * Handle the Inventario "force deleted" event.
     *
     * @param Inventario $inventario
     * @return void
     */
    public function forceDeleted(Inventario $inventario)
    {
        //
    }
}
