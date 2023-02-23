<?php

namespace App\Observers\FondosRotativos\Saldo;

use App\Models\FondosRotativos\Saldo\SaldoGrupo;
use App\Models\FondosRotativos\Viatico\DetalleViatico;
use App\Models\FondosRotativos\Viatico\EstadoViatico;
use App\Models\FondosRotativos\Viatico\Viatico ;
use Spatie\LaravelIgnition\Recorders\DumpRecorder\Dump;

class GastosObserver
{
    /**
     * Handle the Viatico "created" event.
     *
     * @param  \App\Models\Viatico  $viatico
     * @return void
     */
    public function created(Viatico $gasto)
    {
        $datos_detalle = DetalleViatico::where('id', $gasto->detalle)->first();
        if ($datos_detalle->descripcion == '') {
            if ($datos_detalle->autorizacion != 'NO') {
                $this->guardar_gasto($gasto);
            }
        } else {
            if ($datos_detalle->autorizacion == 'NO') {
                $this->guardar_gasto($gasto);
            }
        }

    }

    /**
     * Handle the Viatico "updated" event.
     *
     * @param  \App\Models\Viatico  $viatico
     * @return void
     */
    public function updated(Viatico $gasto)
    {
        $this->guardar_gasto($gasto);
    }

    /**
     * Handle the Viatico "deleted" event.
     *
     * @param  \App\Models\Viatico  $viatico
     * @return void
     */
    public function deleted(Viatico $viatico)
    {
        //
    }

    /**
     * Handle the Viatico "restored" event.
     *
     * @param  \App\Models\Viatico  $viatico
     * @return void
     */
    public function restored(Viatico $viatico)
    {
        //
    }

    /**
     * Handle the Viatico "force deleted" event.
     *
     * @param  \App\Models\Viatico  $viatico
     * @return void
     */
    public function forceDeleted(Viatico $viatico)
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
        $fechaFin = date("Y-m-d", strtotime($fecha. "+$sum days"));
        return array($fechaIni, $fechaFin);
    }
    private function guardar_gasto(Viatico $gasto){
        $saldo_anterior = SaldoGrupo::where('id_usuario', $gasto->id_usuario)->orderBy('id', 'desc')->first();
        $total_saldo_actual = $saldo_anterior !== null ? $saldo_anterior->saldo_actual : 0;
        $saldo = new SaldoGrupo();
        $saldo->fecha = $gasto->fecha_viat;
        $saldo->saldo_anterior = $total_saldo_actual;
        $saldo->saldo_depositado = $gasto->total;
        $saldo->saldo_actual =  $total_saldo_actual-$gasto->total;
        $saldo->fecha_inicio =$this->calcular_fechas( date('Y-m-d', strtotime($gasto->fecha_viat)))[0];
        $saldo->fecha_fin = $this->calcular_fechas( date('Y-m-d', strtotime($gasto->fecha_viat)))[1];;
        $saldo->id_usuario = $gasto->id_usuario;
        $saldo->save();
    }

}
