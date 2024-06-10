<?php

namespace App\Observers\FondosRotativos\Saldo;

use App\Models\FondosRotativos\Saldo\Acreditaciones;
use App\Models\FondosRotativos\Saldo\SaldoGrupo;
use Src\App\FondosRotativos\SaldoService;

class AcreditacionObserver
{
    /**
     * Handle the Acreditaciones "created" event.
     *
     * @param  \App\Models\FondosRotativos\Saldo\Acreditaciones  $acreditaciones
     * @return void
     */
    public function created(Acreditaciones $acreditaciones)
    {
        $this->guardarAcreditacion($acreditaciones);
    }

    /**
     * Handle the Acreditaciones "updated" event.
     *
     * @param  \App\Models\FondosRotativos\Saldo\Acreditaciones  $acreditaciones
     * @return void
     */
    public function updated(Acreditaciones $acreditaciones)
    {
        $this->anulacionAcreditacion($acreditaciones);
   }

    /**
     * Handle the Acreditaciones "deleted" event.
     *
     * @param  \App\Models\Acreditaciones  $acreditaciones
     * @return void
     */
    public function deleted(Acreditaciones $acreditaciones)
    {
        //
    }

    /**
     * Handle the Acreditaciones "restored" event.
     *
     * @param  \App\Models\Acreditaciones  $acreditaciones
     * @return void
     */
    public function restored(Acreditaciones $acreditaciones)
    {
        //
    }

    /**
     * Handle the Acreditaciones "force deleted" event.
     *
     * @param  \App\Models\Acreditaciones  $acreditaciones
     * @return void
     */
    public function forceDeleted(Acreditaciones $acreditaciones)
    {
        //
    }
    private function guardarAcreditacion(Acreditaciones $acreditacion){
        $data = array(
            'fecha' =>  $acreditacion->fecha,
            'monto' =>  $acreditacion->monto,
            'empleado_id' => $acreditacion->id_usuario,
            'tipo' => SaldoService::INGRESO
        );
        SaldoService::guardarSaldo($acreditacion, $data);
    }
    private function anulacionAcreditacion(Acreditaciones $acreditacion){
        $data = array(
            'fecha' =>  $acreditacion->fecha,
            'monto' =>  $acreditacion->monto,
            'empleado_id' => $acreditacion->id_usuario,
            'tipo' => SaldoService::EGRESO
        );
        SaldoService::anularSaldo($acreditacion, $data);    }

}
