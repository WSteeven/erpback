<?php

namespace App\Observers\FondosRotativos\Saldo;

use App\Models\FondosRotativos\Saldo\SaldoGrupo;
use App\Models\FondosRotativos\Saldo\Transferencias;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Src\App\FondosRotativos\SaldoService;

class TransferenciaObserver
{
    /**
     * Handle the Transferencias "created" event.
     *
     * @param  \App\Models\Transferencias  $transferencias
     * @return void
     */
    public function created(Transferencias $transferencia)
    {
    }

    /**
     * Handle the Transferencias "updated" event.
     *
     * @param  \App\Models\Transferencias  $transferencias
     * @return void
     */
    public function updated(Transferencias $transferencia)
    {
        if ($transferencia->estado == Transferencias::APROBADO) {
            $this->guardara_transferencia($transferencia);
        }
        if ($transferencia->estado == Transferencias::ANULADO) {
            if($transferencia->es_devolucion){
                $this->anularTransferenciaEmpleadoEnvia($transferencia);
            }else{
                $this->anularTransferenciaEmpleadoEnvia($transferencia);
                $this->anularTransferenciaEmpleadoRecibe($transferencia);
            }
        }
    }

    /**
     * Handle the Transferencias "deleted" event.
     *
     * @param  \App\Models\Transferencias  $transferencias
     * @return void
     */
    public function deleted(Transferencias $transferencias)
    {
        //
    }

    /**
     * Handle the Transferencias "restored" event.
     *
     * @param  \App\Models\Transferencias  $transferencias
     * @return void
     */
    public function restored(Transferencias $transferencias)
    {
        //
    }

    /**
     * Handle the Transferencias "force deleted" event.
     *
     * @param  \App\Models\Transferencias  $transferencias
     * @return void
     */
    public function forceDeleted(Transferencias $transferencias)
    {
        //
    }

    private function guardara_transferencia(Transferencias $transferencia)
    {
        $this->actualizacionEmpleadoEnvia($transferencia);
        if (!$transferencia->es_devolucion) {
            $this->actualizacionEmpleadoRecibe($transferencia);
        }
    }
    private function actualizacionEmpleadoEnvia(Transferencias $transferencia)
    {
        $data = array(
            'fecha' =>  $transferencia->created_at,
            'monto' =>  $transferencia->monto,
            'empleado_id' => $transferencia->usuario_envia_id,
            'tipo' => SaldoService::EGRESO
        );
        SaldoService::guardarSaldo($transferencia, $data);
    }
    private function actualizacionEmpleadoRecibe(Transferencias $transferencia)
    {
        $data = array(
            'fecha' =>  $transferencia->created_at,
            'monto' =>  $transferencia->monto,
            'empleado_id' => $transferencia->usuario_recibe_id,
            'tipo' => SaldoService::INGRESO
        );
        SaldoService::guardarSaldo($transferencia, $data);
    }

    private function anularTransferenciaEmpleadoEnvia(Transferencias $transferencia)
    {
        $data = array(
            'fecha' =>  $transferencia->created_at,
            'monto' =>  $transferencia->monto,
            'empleado_id' => $transferencia->usuario_envia_id,
            'tipo' => SaldoService::INGRESO
        );
        SaldoService::anularSaldo($transferencia, $data);
    }
    private function anularTransferenciaEmpleadoRecibe(Transferencias $transferencia)
    {
        $data = array(
            'fecha' =>  $transferencia->created_at,
            'monto' =>  $transferencia->monto,
            'empleado_id' => $transferencia->usuario_recibe_id,
            'tipo' => SaldoService::EGRESO
        );
        SaldoService::anularSaldo($transferencia, $data);
    }

}
