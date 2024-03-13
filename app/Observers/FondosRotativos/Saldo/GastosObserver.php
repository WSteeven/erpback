<?php

namespace App\Observers\FondosRotativos\Saldo;

use App\Models\FondosRotativos\Saldo\SaldoGrupo;
use App\Models\FondosRotativos\Gasto\DetalleViatico;
use App\Models\FondosRotativos\Gasto\EstadoGasto;
use App\Models\FondosRotativos\Gasto\Gasto;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Spatie\LaravelIgnition\Recorders\DumpRecorder\Dump;
use Src\App\FondosRotativos\SaldoService;

class GastosObserver
{
    /**
     * Handle the Gasto "created" event.
     *
     * @param  \App\Models\FondosRotativos\Gasto\Gasto  $gasto
     * @return void
     */
    public function created(Gasto $gasto)
    {
    }

    /**
     * Handle the Gasto "updated" event.
     *
     * @param  \App\Models\FondosRotativos\Gasto\Gasto  $gasto
     * @return void
     */
    public function updated(Gasto $gasto)
    {
        if ($gasto->estado == Gasto::APROBADO) $this->guardarGasto($gasto);
        if ($gasto->estado == Gasto::ANULADO) $this->revertirCambios($gasto);
    }

    /**
     * Handle the Gasto "deleted" event.
     *
     * @param  \App\Models\Gasto  $gasto
     * @return void
     */
    public function deleted(Gasto $gasto)
    {
        //
    }

    /**
     * Handle the Gasto "restored" event.
     *
     * @param  \App\Models\Gasto  $gasto
     * @return void
     */
    public function restored(Gasto $gasto)
    {
        //
    }

    /**
     * Handle the Gasto "force deleted" event.
     *
     * @param  \App\Models\Gasto  $gasto
     * @return void
     */
    public function forceDeleted(Gasto $gasto)
    {
        //
    }

   /**
    * La función `revertirCambios` anula un gasto específico guardando los datos necesarios en un
    * objeto `SaldoGrupo`.
    *
    * @param gasto El parámetro `gasto` parece ser un objeto que contiene las siguientes propiedades o
    * atributos:
    */
    private function revertirCambios($gasto)
    {
        $data = array(
            'fecha' =>  $gasto->fecha_viat,
            'monto' =>  $gasto->total,
            'empleado_id' => $gasto->id_usuario,
            'tipo' => SaldoService::ANULACION
        );
        SaldoService::guardarSaldo($gasto, $data);
    }

/**
 * La función `guardarGasto` guarda un objeto Gasto como una entrada de SaldoGrupo con campos de datos
 * específicos.
 *
 * @param Gasto gasto El parámetro `gasto` es un objeto de tipo `Gasto`. Contiene las siguientes
 * propiedades:
 */
    private function guardarGasto(Gasto $gasto)
    {
        $data = array(
            'fecha' =>  $gasto->fecha_viat,
            'monto' =>  $gasto->total,
            'empleado_id' => $gasto->id_usuario,
            'tipo' => SaldoService::EGRESO
        );
        SaldoService::guardarSaldo($gasto, $data);
    }
}
