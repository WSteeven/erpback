<?php

namespace App\Observers\FondosRotativos\Saldo;

use App\Models\FondosRotativos\Saldo\SaldoGrupo;
use App\Models\FondosRotativos\Saldo\Transferencias;
use Illuminate\Support\Facades\Log;

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
        if ($transferencia->estado == 1) {
            $this->guardara_transferencia($transferencia);
        }
        if($transferencia->estado == 4){
            //cancelar transferencia
            $saldo_anterior_envia = SaldoGrupo::where('id_usuario', $transferencia->usuario_envia_id)->orderBy('id', 'desc')->first();
            $total_saldo_actual_usuario_envia = $saldo_anterior_envia !== null ? $saldo_anterior_envia->saldo_actual : 0;
            //Actualizacion de saldo Usuario que envia
            $saldo_envia = new SaldoGrupo();
            $saldo_envia->fecha = $transferencia->created_at;
            $saldo_envia->saldo_anterior = $total_saldo_actual_usuario_envia;
            $saldo_envia->saldo_depositado = $transferencia->monto;
            $saldo_envia->saldo_actual =  $total_saldo_actual_usuario_envia + $transferencia->monto;
            $saldo_envia->fecha_inicio = $this->calcular_fechas(date('Y-m-d', strtotime($transferencia->created_at)))[0];
            $saldo_envia->fecha_fin = $this->calcular_fechas(date('Y-m-d', strtotime($transferencia->created_at)))[1];;
            $saldo_envia->id_usuario = $transferencia->usuario_envia_id;
            $saldo_envia->tipo_saldo = "Anulacion";
            $saldo_envia->save();
            //actualizacion de saldo usuario que recibe
            $saldo_anterior_recibe = SaldoGrupo::where('id_usuario', $transferencia->usuario_recibe_id)->orderBy('id', 'desc')->first();
            $total_saldo_actual_usuario_recibe = $saldo_anterior_recibe !== null ? $saldo_anterior_recibe->saldo_actual : 0;
            $saldo_recibe = new SaldoGrupo();
            $saldo_recibe->fecha = $transferencia->created_at;
            $saldo_recibe->saldo_anterior = $total_saldo_actual_usuario_recibe;
            $saldo_recibe->saldo_depositado = $transferencia->monto;
            $saldo_recibe->saldo_actual =  $total_saldo_actual_usuario_recibe - $transferencia->monto;
            $saldo_recibe->fecha_inicio = $this->calcular_fechas(date('Y-m-d', strtotime($transferencia->created_at)))[0];
            $saldo_recibe->fecha_fin = $this->calcular_fechas(date('Y-m-d', strtotime($transferencia->created_at)))[1];;
            $saldo_recibe->id_usuario = $transferencia->usuario_recibe_id;
            $saldo_recibe->tipo_saldo = "Anulacion";
            $saldo_recibe->save();
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
    private function calcular_fechas($fecha)
    {
        $array_dias['Sunday'] = 0;
        $array_dias['Monday'] = 1;
        $array_dias['Tuesday'] = 2;
        $array_dias['Wednesday'] = 3;
        $array_dias['Thursday'] = 4;
        $array_dias['Friday'] = 5;
        $array_dias['Saturday'] = 6;

        $dia_actual = $array_dias[date('l', strtotime($fecha))];

        $rest = $dia_actual + 1;
        $sum = 5 - $dia_actual;
        $fechaIni = date("Y-m-d", strtotime($fecha . "-$rest days"));
        $fechaFin = date("Y-m-d", strtotime($fecha . "+$sum days"));
        return array($fechaIni, $fechaFin);
    }
    private function guardara_transferencia($transferencia){
        $saldo_anterior_envia = SaldoGrupo::where('id_usuario', $transferencia->usuario_envia_id)->orderBy('id', 'desc')->first();
        $total_saldo_actual_usuario_envia = $saldo_anterior_envia !== null ? $saldo_anterior_envia->saldo_actual : 0;
        $saldo_anterior_recibe = SaldoGrupo::where('id_usuario', $transferencia->usuario_recibe_id)->orderBy('id', 'desc')->first();
        $total_saldo_actual_usuario_recibe = $saldo_anterior_recibe !== null ? $saldo_anterior_recibe->saldo_actual : 0;
        //Actualizacion de saldo Usuario que envia
        $saldo_envia = new SaldoGrupo();
        $saldo_envia->fecha = $transferencia->created_at;
        $saldo_envia->saldo_anterior = $total_saldo_actual_usuario_envia;
        $saldo_envia->saldo_depositado = $transferencia->monto;
        $saldo_envia->saldo_actual =  $total_saldo_actual_usuario_envia - $transferencia->monto;
        $saldo_envia->fecha_inicio = $this->calcular_fechas(date('Y-m-d', strtotime($transferencia->created_at)))[0];
        $saldo_envia->fecha_fin = $this->calcular_fechas(date('Y-m-d', strtotime($transferencia->created_at)))[1];;
        $saldo_envia->id_usuario = $transferencia->usuario_envia_id;
        $saldo_envia->tipo_saldo = "Egreso";
        $saldo_envia->save();
       // $saldo_actual = $total_saldo_actual_usuario_envia - $transferencia->monto;
       // SaldoGrupo::crearSaldoGrupo($transferencia->created_at,$total_saldo_actual_usuario_envia,$transferencia->monto,$saldo_actual,$this->calcular_fechas(date('Y-m-d', strtotime($transferencia->created_at)))[0],$this->calcular_fechas(date('Y-m-d', strtotime($transferencia->created_at)))[1],$transferencia->usuario_envia_id,"Egreso",$transferencia);
        //Actualizacion de saldo Usuario que recibe
        if ($transferencia->usuario_recibe_id != null && $transferencia->usuario_recibe_id != 10) {
            $saldo_recibe = new SaldoGrupo();
            $saldo_recibe->fecha = $transferencia->created_at;
            $saldo_recibe->saldo_anterior = $total_saldo_actual_usuario_recibe;
            $saldo_recibe->saldo_depositado = $transferencia->monto;
            $saldo_recibe->saldo_actual =  $total_saldo_actual_usuario_recibe + $transferencia->monto;
            $saldo_recibe->fecha_inicio = $this->calcular_fechas(date('Y-m-d', strtotime($transferencia->created_at)))[0];
            $saldo_recibe->fecha_fin = $this->calcular_fechas(date('Y-m-d', strtotime($transferencia->created_at)))[1];;
            $saldo_recibe->id_usuario = $transferencia->usuario_recibe_id;
            $saldo_recibe->tipo_saldo = "Ingreso";
            $saldo_recibe->save();
           // $saldo_actual = $total_saldo_actual_usuario_recibe + $transferencia->monto;
           // SaldoGrupo::crearSaldoGrupo($transferencia->created_at,$total_saldo_actual_usuario_recibe,$transferencia->monto,$saldo_actual,$this->calcular_fechas(date('Y-m-d', strtotime($transferencia->created_at)))[0],$this->calcular_fechas(date('Y-m-d', strtotime($transferencia->created_at)))[1],$transferencia->usuario_envia_id,"Egreso",$transferencia);

        }
    }
}
