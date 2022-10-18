<?php

namespace App\Observers;

use App\Models\ControlStock;
use App\Models\Inventario;
use Illuminate\Support\Facades\Log;

class InventarioObserver
{
    /**
     * Handle the Inventario "created" event.
     *
     * @param  \App\Models\Inventario  $inventario
     * @return void
     */
    public function created(Inventario $inventario)
    {
        // Log::channel('testing')->info('Log', ['created InventarioObserver', $inventario]);
        
        $control_stock = ControlStock::where('detalle_id', $inventario->detalle_id)
            ->where('sucursal_id', $inventario->sucursal_id)
            ->where('cliente_id', $inventario->cliente_id)->first();

        // Log::channel('testing')->info('Log', ['control de stock en created InventarioObserver', $control_stock]);
        if ($control_stock) {
            $control_stock->update([
                'estado' => ControlStock::calcularEstado(ControlStock::controlExistencias($inventario->detalle_id, $inventario->sucursal_id, $inventario->cliente_id), $control_stock->minimo, $control_stock->reorden)
            ]);
        }
    }

    /**
     * Handle the Inventario "updated" event.
     *
     * @param  \App\Models\Inventario  $inventario
     * @return void
     */
    public function updated(Inventario $inventario)
    {
        // Log::channel('testing')->info('Log', ['updated InventarioObserver', $inventario]);

        $control_stock = ControlStock::where('detalle_id', $inventario->detalle_id)
            ->where('sucursal_id', $inventario->sucursal_id)
            ->where('cliente_id', $inventario->cliente_id)->first();
        // Log::channel('testing')->info('Log', ['control de stock en InventarioObserver', $control_stock]);
        if ($control_stock) {
            $control_stock->update([
                'estado' => ControlStock::calcularEstado(ControlStock::controlExistencias($inventario->detalle_id, $inventario->sucursal_id, $inventario->cliente_id), $control_stock->minimo, $control_stock->reorden)
            ]);
        }
    }

    /**
     * Handle the Inventario "deleted" event.
     *
     * @param  \App\Models\Inventario  $inventario
     * @return void
     */
    public function deleted(Inventario $inventario)
    {
        $control_stock = ControlStock::where('detalle_id', $inventario->detalle_id)
            ->where('sucursal_id', $inventario->sucursal_id)
            ->where('cliente_id', $inventario->cliente_id)->first();

        // Log::channel('testing')->info('Log', ['control de stock en created InventarioObserver', $control_stock]);
        if ($control_stock) {
            $control_stock->update([
                'estado' => ControlStock::calcularEstado(ControlStock::controlExistencias($inventario->detalle_id, $inventario->sucursal_id, $inventario->cliente_id), $control_stock->minimo, $control_stock->reorden)
            ]);
        }
    }

    /**
     * Handle the Inventario "restored" event.
     *
     * @param  \App\Models\Inventario  $inventario
     * @return void
     */
    public function restored(Inventario $inventario)
    {
        //
    }

    /**
     * Handle the Inventario "force deleted" event.
     *
     * @param  \App\Models\Inventario  $inventario
     * @return void
     */
    public function forceDeleted(Inventario $inventario)
    {
        //
    }
}
