<?php

namespace App\Observers\FondosRotativos\Saldo;

use App\Models\FondosRotativos\Gasto\Gasto;
use App\Models\FondosRotativos\Saldo\Saldo;
use Src\App\FondosRotativos\SaldoService;
use Throwable;

class GastosObserver
{
    /**
     * Handle the Gasto "created" event.
     *
     * @param Gasto $gasto
     * @return void
     */
    public function created(Gasto $gasto)
    {
    }

    /**
     * Handle the Gasto "updated" event.
     *
     * @param Gasto $gasto
     * @return void
     * @throws Throwable
     */
    public function updated(Gasto $gasto)
    {
        if ($gasto->estado == Gasto::APROBADO) $this->guardarGasto($gasto);
        if($gasto->estado == Gasto::RECHAZADO) $this->anularGastoEscritoEnSaldo($gasto);
        if ($gasto->estado == Gasto::ANULADO) $this->revertirCambios($gasto);
    }

    /**
     * Handle the Gasto "deleted" event.
     *
     * @param Gasto $gasto
     * @return void
     */
    public function deleted(Gasto $gasto)
    {
        //
    }

    /**
     * Handle the Gasto "restored" event.
     *
     * @param Gasto $gasto
     * @return void
     */
    public function restored(Gasto $gasto)
    {
        //
    }

    /**
     * Handle the Gasto "force deleted" event.
     *
     * @param Gasto $gasto
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
     * @param Gasto $gasto El parámetro `gasto` parece ser un objeto que contiene las siguientes propiedades o
     * atributos:
     * @throws Throwable
     */
    private function revertirCambios(Gasto $gasto)
    {
        $data = array(
            'fecha' =>  $gasto->fecha_viat,
            'monto' =>  $gasto->total,
            'empleado_id' => $gasto->id_usuario,
            'tipo' => SaldoService::INGRESO
        );
        SaldoService::anularSaldo($gasto, $data);
    }

    /**
     * La función `guardarGasto` guarda un objeto Gasto como una entrada de SaldoGrupo con campos de datos
     * específicos.
     *
     * @param Gasto $gasto El parámetro `gasto` es un objeto de tipo `Gasto`. Contiene las siguientes
     * propiedades:
     * @throws Throwable
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

    /**
     * Aquí se verifica si existe un registro de saldo para el gasto dado. Si existe, se anula,
     * si no existe o si hay más de uno (significa que ya ha sido anulado), no se hace nada.
     * @param Gasto $gasto
     * @return void
     * @throws Throwable
     */
    private function anularGastoEscritoEnSaldo(Gasto $gasto)
    {
        $existeRegistroSaldo  = Saldo::where('saldoable_id', $gasto->id)
            ->where('saldoable_type', Gasto::class)
            ->get();

        if($existeRegistroSaldo->count()==0 || $existeRegistroSaldo->count()>1) return;

        //entra aquí si solo hay uno
        $registroSaldo = $existeRegistroSaldo->first();
        if ($registroSaldo->tipo_saldo == Saldo::EGRESO){
            // Como si se encontró un registro de saldo, se procede a crear otro asiento contable para anular ese registro
            SaldoService::anularSaldo($gasto, [
                'fecha'=>$gasto->fecha_viat,
                'monto'=>$gasto->total,
                'empleado_id'=>$gasto->id_usuario,
                'tipo'=>SaldoService::INGRESO]);
        }
    }
}
