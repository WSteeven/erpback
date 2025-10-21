<?php

namespace App\Observers\FondosRotativos\Saldo;

use App\Models\FondosRotativos\Saldo\Transferencias;
use Src\App\FondosRotativos\SaldoService;

class TransferenciaObserver
{


    /**
     * Handle the Transferencias "updated" event.
     *
     * @param Transferencias $transferencia
     * @return void
     */
    public function updated(Transferencias $transferencia)
    {
        if ($transferencia->estado == Transferencias::APROBADO) {
            $this->guardarTransferencia($transferencia);
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





    private function guardarTransferencia(Transferencias $transferencia)
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
