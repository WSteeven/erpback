<?php

namespace Src\App\FondosRotativos;

use App\Models\Empleado;
use App\Models\FondosRotativos\Saldo\AcreditacionSemana;
use App\Models\FondosRotativos\Saldo\ValorAcreditar;
use App\Models\FondosRotativos\UmbralFondosRotativos;
use Illuminate\Database\Eloquent\Collection;

class AcreditacionSemanalService
{

    public static function asignarAcreditaciones(Collection $umbral_fondos_rotativos, AcreditacionSemana $acreditacion_semana)
    {
        $acreditaciones = [];
        foreach ($umbral_fondos_rotativos as $key => $umbral) {
            $empleado = Empleado::where('id', $umbral->empleado_id)->first();
            $saldo_actual = SaldoService::obtenerSaldoActual($empleado);
            $valorRecibir = $umbral->valor_minimo - $saldo_actual;
            $numeroRedondeado = $valorRecibir;
            if ($saldo_actual == 0) {
                $numeroRedondeado = $valorRecibir;
            } else {
                $numeroRedondeado = $valorRecibir > 0 ? (ceil($valorRecibir / 10) * 10) : 0;
            }
            $acreditaciones[] =  [
                'empleado_id' => $umbral->empleado_id,
                'acreditacion_semana_id' => $acreditacion_semana->id,
                'monto_generado' => $numeroRedondeado,
                'monto_modificado' => $numeroRedondeado,
            ];
        }
        return $acreditaciones;
    }
    public static function refrescarAcreditacion(Collection $acreditaciones_semanales, AcreditacionSemana $acreditacion_semana){
        foreach ($acreditaciones_semanales as $key => $valor_acreditar) {
            $empleado = Empleado::where('empleado_id', $valor_acreditar->empleado_id)->first();
            $saldo_actual = SaldoService::obtenerSaldoActual($empleado);
            $valor_recibir = self::umbralEmpleado($valor_acreditar->empleado_id) - $saldo_actual;
            $numero_redondeado = $valor_recibir;
            if ($saldo_actual == 0) {
                $numero_redondeado = $valor_recibir;
            } else {
                $numero_redondeado = $valor_recibir > 0 ? (ceil($valor_recibir / 10) * 10) : 0;
            }
            $valor_acreditar =  ValorAcreditar::where('acreditacion_semana_id', $acreditacion_semana->id)
                ->where('empleado_id', $valor_acreditar->empleado_id)->first();

            $valor_acreditar->update(array(
                'empleado_id' => $valor_acreditar->empleado_id,
                'acreditacion_semana_id' => $valor_acreditar->acreditacion_semana_id,
                'monto_generado' => $numero_redondeado,
                'monto_modificado' => $valor_acreditar->monto_modificado,
            ));
        }
    }
    public static function umbralEmpleado($empleado_id)
    {
        $umbral = UmbralFondosRotativos::where('empleado_id', $empleado_id)->first();
        return $umbral != null ? $umbral->valor_minimo : 0;
    }
}
