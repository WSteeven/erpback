<?php

namespace App\Observers\FondosRotativos\Saldo;

use App\Models\FondosRotativos\Saldo\Acreditaciones;
use App\Models\FondosRotativos\Saldo\SaldoGrupo;

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
        $this->guardar_acreditacion($acreditaciones);
    }

    /**
     * Handle the Acreditaciones "updated" event.
     *
     * @param  \App\Models\FondosRotativos\Saldo\Acreditaciones  $acreditaciones
     * @return void
     */
    public function updated(Acreditaciones $acreditaciones)
    {
        $this->guardar_anulacion_acreditacion($acreditaciones);
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
    private function guardar_acreditacion(Acreditaciones $acreditacion){
        $saldo_anterior = SaldoGrupo::where('id_usuario', $acreditacion->id_usuario)->orderBy('id', 'desc')->first();
        $total_saldo_actual = $saldo_anterior !== null ? $saldo_anterior->saldo_actual : 0;
        //$saldo_actual = $total_saldo_actual+$acreditacion->monto;
        $saldo = new SaldoGrupo();
        $saldo->fecha = $acreditacion->fecha;
        $saldo->saldo_anterior = $total_saldo_actual;
        $saldo->saldo_depositado = $acreditacion->monto;
        $saldo->saldo_actual =  $total_saldo_actual+$acreditacion->monto;
        $saldo->fecha_inicio =$this->calcular_fechas( date('Y-m-d', strtotime($acreditacion->fecha)))[0];
        $saldo->fecha_fin = $this->calcular_fechas( date('Y-m-d', strtotime($acreditacion->fecha)))[1];;
        $saldo->id_usuario = $acreditacion->id_usuario;
        $saldo->tipo_saldo = "Ingreso";
        $saldo->save();
        //SaldoGrupo::crearSaldoGrupo($acreditacion->fecha,$total_saldo_actual,$acreditacion->monto,$saldo_actual,$this->calcular_fechas( date('Y-m-d', strtotime($acreditacion->fecha)))[0],$this->calcular_fechas( date('Y-m-d', strtotime($acreditacion->fecha)))[1],$acreditacion->id_usuario,"Ingreso",$acreditacion);
    }
    private function guardar_anulacion_acreditacion(Acreditaciones $acreditacion){
        $saldo_anterior = SaldoGrupo::where('id_usuario', $acreditacion->id_usuario)->orderBy('id', 'desc')->first();
        $total_saldo_actual = $saldo_anterior !== null ? $saldo_anterior->saldo_actual : 0;
        //$saldo_actual = $total_saldo_actual+$acreditacion->monto;
        $saldo = new SaldoGrupo();
        $saldo->fecha = $acreditacion->fecha;
        $saldo->saldo_anterior = $total_saldo_actual;
        $saldo->saldo_depositado = $acreditacion->monto;
        $saldo->saldo_actual =  $total_saldo_actual-$acreditacion->monto;
        $saldo->fecha_inicio =$this->calcular_fechas( date('Y-m-d', strtotime($acreditacion->fecha)))[0];
        $saldo->fecha_fin = $this->calcular_fechas( date('Y-m-d', strtotime($acreditacion->fecha)))[1];;
        $saldo->id_usuario = $acreditacion->id_usuario;
        $saldo->tipo_saldo = "Anulacion";
        $saldo->save();
        //SaldoGrupo::crearSaldoGrupo($acreditacion->fecha,$total_saldo_actual,$acreditacion->monto,$saldo_actual,$this->calcular_fechas( date('Y-m-d', strtotime($acreditacion->fecha)))[0],$this->calcular_fechas( date('Y-m-d', strtotime($acreditacion->fecha)))[1],$acreditacion->id_usuario,"Anulacion",$acreditacion);
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
        $fechaFin = date("Y-m-d", strtotime($fecha. "+$sum days"));
        return array($fechaIni, $fechaFin);
    }
}
