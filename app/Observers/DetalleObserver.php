<?php

namespace App\Observers;

use App\Models\CodigoCliente;
use App\Models\DetalleProducto;
use Illuminate\Support\Facades\Log;
use Src\Shared\Utils;

class DetalleObserver
{
    /**
     * Handle the DetalleProducto "created" event.
     *
     * @param DetalleProducto $detalleProducto
     * @return void
     */
    public function created(DetalleProducto $detalleProducto)
    {
        Log::channel('testing')->info('Log', ['entro al observer de created', $detalleProducto]);
        CodigoCliente::create([
            'codigo'=>'JP-'.Utils::generarCodigoConLongitud($detalleProducto->id, 6),
            'detalle_id'=>$detalleProducto->id
        ]);
    }

    /**
     * Handle the DetalleProducto "updated" event.
     *
     * @param DetalleProducto $detalleProducto
     * @return void
     */
    public function updated(DetalleProducto $detalleProducto)
    {
        //
    }

    /**
     * Handle the DetalleProducto "deleted" event.
     *
     * @param DetalleProducto $detalleProducto
     * @return void
     */
    public function deleted(DetalleProducto $detalleProducto)
    {
        //
    }

    /**
     * Handle the DetalleProducto "restored" event.
     *
     * @param DetalleProducto $detalleProducto
     * @return void
     */
    public function restored(DetalleProducto $detalleProducto)
    {
        //
    }

    /**
     * Handle the DetalleProducto "force deleted" event.
     *
     * @param DetalleProducto $detalleProducto
     * @return void
     */
    public function forceDeleted(DetalleProducto $detalleProducto)
    {
        //
    }
}
