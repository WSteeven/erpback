<?php

namespace App\Observers\FondosRotativos\Saldo;

use App\Models\FondosRotativos\Saldo\SaldoGrupo;
use App\Models\FondosRotativos\Gasto\DetalleViatico;
use App\Models\FondosRotativos\Gasto\EstadoGasto;
use App\Models\FondosRotativos\Gasto\Gasto;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Spatie\LaravelIgnition\Recorders\DumpRecorder\Dump;

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
        if ($gasto->estado == 1) $this->guardar_gasto($gasto);
        if ($gasto->estado == 4) $this->revertir_cambios($gasto);
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
    private function revertir_cambios($gasto)
    {
        $fecha = $gasto->fecha_viat;
        $ultimo_registro_semana = SaldoGrupo::whereBetween('fecha', [
            Carbon::parse($fecha)->startOfWeek(),
            Carbon::parse($fecha)->endOfWeek()
        ])
            ->where('id_usuario', $gasto->id_usuario)
            ->orderBy('fecha', 'desc')
            ->first();
        if ($ultimo_registro_semana === null) {
            // Si no hay registro de saldo para la semana, obtener el último registro de saldo anterior
            $saldo_anterior = SaldoGrupo::where('id_usuario', $gasto->id_usuario)->orderBy('fecha', 'desc')->first();
            $total_saldo_actual = $saldo_anterior !== null ? $saldo_anterior->saldo_actual : 0;
        } else {
            $total_saldo_actual = $ultimo_registro_semana->saldo_actual;
        }
        $saldo = new SaldoGrupo();
        $saldo->fecha = $gasto->fecha_viat;
        $saldo->saldo_anterior = $total_saldo_actual;
        $saldo->saldo_depositado = $gasto->total;
        $saldo->saldo_actual =  $total_saldo_actual + $gasto->total;
        $saldo->fecha_inicio = $this->calcular_fechas(date('Y-m-d', strtotime($gasto->fecha_viat)))[0];
        $saldo->fecha_fin = $this->calcular_fechas(date('Y-m-d', strtotime($gasto->fecha_viat)))[1];
        $saldo->id_usuario = $gasto->id_usuario;
        $saldo->tipo_saldo = "Anulacion";
        $saldo->save();
        $fecha_actual = Carbon::now();
        $fecha_gasto = Carbon::parse($gasto->fecha_viat);
        if ($fecha_gasto->isSameWeek($fecha_actual) == false) {
            // La fecha del gasto está dentro de la semana actual
           $this->encuadre($ultimo_registro_semana->id,$gasto->id_usuario,$gasto,$saldo->saldo_actual);
        }
    }
    private function encuadre($id,$usuario,Gasto $gasto,$total_saldo_actual)
    {
        //penultimo registro de saldo_grupo
        $penultimo_registro = SaldoGrupo::orderBy('id', 'desc')->skip(1)->first();
        Log::channel('testing')->info('id: ' . $id);
        Log::channel('testing')->info('penultimo_registro: ' . $penultimo_registro->id);
        // Obtener los gastos de tipo_saldo Ingreso
        $ingresos = SaldoGrupo::whereBetween('id', [
            $id,
            $penultimo_registro->id
        ])
            ->where('tipo_saldo', 'Ingreso')
            ->where('id_usuario', $usuario)
            ->sum('saldo_depositado');
        Log::channel('testing')->info('Ingresos: ' . $ingresos);

        // Obtener los gastos de tipo_saldo Egreso de la semana actual
        $egresos = SaldoGrupo::whereBetween('id', [
            $id,
            $penultimo_registro->id
        ])
            ->where('tipo_saldo', 'Egreso')
            ->where('id_usuario', $usuario)
            ->sum('saldo_depositado');
        Log::channel('testing')->info('Egresos: ' . $egresos);

        // Calcular la diferencia entre los ingresos y los egresos
        $diferencia = $ingresos - $egresos;
        //Guardar el encuadre
        $saldo = new SaldoGrupo();
        $saldo->fecha = $gasto->fecha_viat;
        $saldo->saldo_anterior = $total_saldo_actual;
        $saldo->saldo_depositado = $diferencia;
        $saldo->saldo_actual =  $total_saldo_actual + $diferencia;
        $saldo->fecha_inicio = $this->calcular_fechas(date('Y-m-d', strtotime($gasto->fecha_viat)))[0];
        $saldo->fecha_fin = $this->calcular_fechas(date('Y-m-d', strtotime($gasto->fecha_viat)))[1];
        $saldo->id_usuario = $gasto->id_usuario;
        $saldo->tipo_saldo = "Encuadre";
        $saldo->save();
    }

    private function guardar_gasto(Gasto $gasto)
    {
        $saldo_anterior = SaldoGrupo::where('id_usuario', $gasto->id_usuario)->orderBy('id', 'desc')->first();
        $total_saldo_actual = $saldo_anterior !== null ? $saldo_anterior->saldo_actual : 0;
        $saldo = new SaldoGrupo();
        $saldo->fecha = $gasto->fecha_viat;
        $saldo->saldo_anterior = $total_saldo_actual;
        $saldo->saldo_depositado = $gasto->total;
        $saldo->saldo_actual =  $total_saldo_actual - $gasto->total;
        $saldo->fecha_inicio = $this->calcular_fechas(date('Y-m-d', strtotime($gasto->fecha_viat)))[0];
        $saldo->fecha_fin = $this->calcular_fechas(date('Y-m-d', strtotime($gasto->fecha_viat)))[1];;
        $saldo->id_usuario = $gasto->id_usuario;
        $saldo->tipo_saldo = "Egreso";
        $saldo->save();
    }
}
