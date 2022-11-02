<?php

namespace App\Observers;

use App\Models\Inventario;
use App\Models\InventarioPrestamoTemporal;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class InventarioPrestamoTemporalObserver
{
    /**
     * Handle the InventarioPrestamoTemporal "created" event.
     *
     * @param  \App\Models\InventarioPrestamoTemporal  $inventarioPrestamoTemporal
     * @return void
     */
    public function created(InventarioPrestamoTemporal $inventarioPrestamoTemporal)
    {
        Log::channel('testing')->info('Log', ['created InventarioPrestamoTemporalObserver', $inventarioPrestamoTemporal]);
        $item = Inventario::find($inventarioPrestamoTemporal->inventario_id);
        DB::table('inventarios')->where('id', $inventarioPrestamoTemporal->inventario_id)->update([
            'cantidad' => $item->cantidad - $inventarioPrestamoTemporal->cantidad,
            'prestados' => $item->prestados + $inventarioPrestamoTemporal->cantidad,
        ]);
    }

    /**
     * Handle the InventarioPrestamoTemporal "updated" event.
     *
     * @param  \App\Models\InventarioPrestamoTemporal  $inventarioPrestamoTemporal
     * @return void
     */
    public function updated(InventarioPrestamoTemporal $inventarioPrestamoTemporal)
    {
        Log::channel('testing')->info('Log', ['updated InventarioPrestamoTemporalObserver', $inventarioPrestamoTemporal]);
        $item = Inventario::find($inventarioPrestamoTemporal->inventario_id);
        DB::table('inventarios')->where('id', $inventarioPrestamoTemporal->inventario_id)->update([
            'cantidad' => $item->cantidad + $inventarioPrestamoTemporal->cantidad,
            'prestados' => $item->prestados - $inventarioPrestamoTemporal->cantidad,
        ]);
    }

    /**
     * Handle the InventarioPrestamoTemporal "deleted" event.
     *
     * @param  \App\Models\InventarioPrestamoTemporal  $inventarioPrestamoTemporal
     * @return void
     */
    public function deleted(InventarioPrestamoTemporal $inventarioPrestamoTemporal)
    {
        //
    }

    /**
     * Handle the InventarioPrestamoTemporal "restored" event.
     *
     * @param  \App\Models\InventarioPrestamoTemporal  $inventarioPrestamoTemporal
     * @return void
     */
    public function restored(InventarioPrestamoTemporal $inventarioPrestamoTemporal)
    {
        //
    }

    /**
     * Handle the InventarioPrestamoTemporal "force deleted" event.
     *
     * @param  \App\Models\InventarioPrestamoTemporal  $inventarioPrestamoTemporal
     * @return void
     */
    public function forceDeleted(InventarioPrestamoTemporal $inventarioPrestamoTemporal)
    {
        //
    }
}
