<?php

namespace App\Observers\FondosRotativos\Saldo;

use App\Models\FondosRotativos\Saldo\Acreditaciones;
use Src\App\FondosRotativos\SaldoService;
use Throwable;

class AcreditacionObserver
{
    /**
     * Handle the Acreditaciones "created" event.
     *
     * @param Acreditaciones $acreditaciones
     * @return void
     * @throws Throwable
     */
    public function created(Acreditaciones $acreditaciones)
    {
        $this->guardarAcreditacion($acreditaciones);
    }

    /**
     * Handle the Acreditaciones "updated" event.
     *
     * @param Acreditaciones $acreditaciones
     * @return void
     * @throws Throwable
     */
    public function updated(Acreditaciones $acreditaciones)
    {
        $this->anulacionAcreditacion($acreditaciones);
   }

    /**
     * Handle the Acreditaciones "deleted" event.
     *
     * @param Acreditaciones $acreditaciones
     * @return void
     */
    public function deleted(Acreditaciones $acreditaciones)
    {
        //
    }

    /**
     * Handle the Acreditaciones "restored" event.
     *
     * @param Acreditaciones $acreditaciones
     * @return void
     */
    public function restored(Acreditaciones $acreditaciones)
    {
        //
    }

    /**
     * Handle the Acreditaciones "force deleted" event.
     *
     * @param Acreditaciones $acreditaciones
     * @return void
     */
    public function forceDeleted(Acreditaciones $acreditaciones)
    {
        //
    }

    /**
     * @throws Throwable
     */
    private function guardarAcreditacion(Acreditaciones $acreditacion){
        $data = array(
            'fecha' =>  $acreditacion->fecha,
            'monto' =>  $acreditacion->monto,
            'empleado_id' => $acreditacion->id_usuario,
            'tipo' => SaldoService::INGRESO
        );
        SaldoService::guardarSaldo($acreditacion, $data);
    }

    /**
     * @throws Throwable
     */
    private function anulacionAcreditacion(Acreditaciones $acreditacion){
        $data = array(
            'fecha' =>  $acreditacion->fecha,
            'monto' =>  $acreditacion->monto,
            'empleado_id' => $acreditacion->id_usuario,
            'tipo' => SaldoService::EGRESO
        );
        SaldoService::anularSaldo($acreditacion, $data);    }

}
