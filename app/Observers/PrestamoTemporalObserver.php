<?php

namespace App\Observers;

use App\Models\Inventario;
use App\Models\InventarioPrestamoTemporal;
use App\Models\PrestamoTemporal;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PrestamoTemporalObserver
{
    /**
     * Handle the PrestamoTemporal "created" event.
     *
     * @param  \App\Models\PrestamoTemporal  $prestamoTemporal
     * @return void
     */
    public function created(PrestamoTemporal $prestamoTemporal)
    {
        //
    }

    /**
     * Handle the PrestamoTemporal "updated" event.
     *
     * @param  \App\Models\PrestamoTemporal  $prestamoTemporal
     * @return void
     */
    public function updated(PrestamoTemporal $prestamoTemporal)
    {
        Log::channel('testing')->info('Log', ['updated PrestamoTemporalObserver', $prestamoTemporal]);
        if ($prestamoTemporal->estado === 'DEVUELTO') {
            Log::channel('testing')->info('Log', ['updated detalles', $prestamoTemporal->detalles()->get()]);
            foreach ($prestamoTemporal->detalles()->get() as $detalle) {
                $item = Inventario::find($detalle['id']);
                Log::channel('testing')->info('Log', ['updated detalles-cantidad', $detalle['pivot']->cantidad, $item]);
                
                try{
                    DB::table('inventarios')->where('id', $detalle['id'])->update([
                        'cantidad' => $item->cantidad + $detalle['pivot']->cantidad,
                        'prestados' => $item->prestados - $detalle['pivot']->cantidad,
                    ]);
                    Log::channel('testing')->info('Log', ['registro actualizado en el inventario', $detalle['cantidad']]);
                }catch(Exception $e){
                    Log::channel('testing')->info('Log', ['ERROR: ', $e->getMessage()]);
                }
            }
        }
        /* $item = Inventario::find($inventarioPrestamoTemporal->inventario_id);
        DB::table('inventarios')->where('id', $inventarioPrestamoTemporal->inventario_id)->update([
            'cantidad' => $item->cantidad + $inventarioPrestamoTemporal->cantidad,
            'prestados' => $item->prestados - $inventarioPrestamoTemporal->cantidad,
        ]); */
    }

    /**
     * Handle the PrestamoTemporal "deleted" event.
     *
     * @param  \App\Models\PrestamoTemporal  $prestamoTemporal
     * @return void
     */
    public function deleted(PrestamoTemporal $prestamoTemporal)
    {
        //
    }

    /**
     * Handle the PrestamoTemporal "restored" event.
     *
     * @param  \App\Models\PrestamoTemporal  $prestamoTemporal
     * @return void
     */
    public function restored(PrestamoTemporal $prestamoTemporal)
    {
        //
    }

    /**
     * Handle the PrestamoTemporal "force deleted" event.
     *
     * @param  \App\Models\PrestamoTemporal  $prestamoTemporal
     * @return void
     */
    public function forceDeleted(PrestamoTemporal $prestamoTemporal)
    {
        //
    }
}
