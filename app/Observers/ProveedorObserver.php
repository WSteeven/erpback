<?php

namespace App\Observers;

use App\Events\ComprasProveedores\CalificacionProveedorEvent;
use App\Events\ComprasProveedores\NotificarProveedorCalificadoEvent;
use App\Models\Proveedor;
use Illuminate\Support\Facades\Log;

class ProveedorObserver
{
    /**
     * Handle the Proveedor "created" event.
     *
     * @param  \App\Models\Proveedor  $proveedor
     * @return void
     */
    public function created(Proveedor $proveedor)
    {
        //
    }

    /**
     * Handle the Proveedor "updated" event.
     *
     * @param  \App\Models\Proveedor  $proveedor
     * @return void
     */
    public function updated(Proveedor $proveedor)
    {
        $mensaje = '';
        // Log::channel('testing')->info('Log', ['Se actualizó el proveedor', $proveedor]);
        if (!$proveedor->notificado) { //si aun no está notificado el proveedor
            if ($proveedor->estado_calificado == Proveedor::CALIFICADO) { //Si el proveedor se ha calificado se notificará al departmento de compras
                switch ($proveedor->calificacion) {
                    case $proveedor->calificacion >= 70:
                        $mensaje = 'El proveedor' . $proveedor->empresa->razon_social . ' ha obtenido una calificación de ' . $proveedor->calificacion . '. Será considerado para trabajar con nosotros';
                        break;
                    default:
                        $mensaje = 'El proveedor' . $proveedor->empresa->razon_social . ' ha obtenido una calificación de ' . $proveedor->calificacion . '. No será considerado para trabajar con nosotros. Por favor busca un mejor proveedor para colaborar con nuestra empresa';
                }

                foreach ($proveedor->departamentos_califican as $departamento) {
                    event(new NotificarProveedorCalificadoEvent($mensaje, $proveedor, null, $departamento['responsable_id'], false));
                }

                $proveedor->notificado = true;
                $proveedor->save();
            }
        }
    }

    /**
     * Handle the Proveedor "deleted" event.
     *
     * @param  \App\Models\Proveedor  $proveedor
     * @return void
     */
    public function deleted(Proveedor $proveedor)
    {
        //
    }

    /**
     * Handle the Proveedor "restored" event.
     *
     * @param  \App\Models\Proveedor  $proveedor
     * @return void
     */
    public function restored(Proveedor $proveedor)
    {
        //
    }

    /**
     * Handle the Proveedor "force deleted" event.
     *
     * @param  \App\Models\Proveedor  $proveedor
     * @return void
     */
    public function forceDeleted(Proveedor $proveedor)
    {
        //
    }
}
