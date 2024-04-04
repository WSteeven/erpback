<?php

namespace Src\App\FondosRotativos;

use App\Models\Empleado;
use App\Models\FondosRotativos\Saldo\AcreditacionSemana;
use App\Models\FondosRotativos\Saldo\Saldo;
use App\Models\FondosRotativos\Saldo\SaldoGrupo;
use App\Models\FondosRotativos\Saldo\ValorAcreditar;
use App\Models\FondosRotativos\UmbralFondosRotativos;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;

class AcreditacionSemanalService
{

    public static function asignarAcreditaciones(Collection $umbral_fondos_rotativos, AcreditacionSemana $acreditacion_semana)
    {
        $acreditaciones = [];
        foreach ($umbral_fondos_rotativos as $key => $umbral) {
            $empleado = Empleado::where('id', $umbral->empleado_id)->first();
            $numeroSemana = explode("FONDO ROTATIVO SEMANA #", $acreditacion_semana->semana)[1];
            $saldo_actual = floatval(self::obtenerSaldo($empleado->id, $numeroSemana, $acreditacion_semana->created_at));
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
                'saldo_empleado' => $saldo_actual,
            ];
        }
        return $acreditaciones;
    }
    public static function refrescarAcreditacion(Collection $acreditaciones_semanales, AcreditacionSemana $acreditacion_semana)
    {
        foreach ($acreditaciones_semanales as $key => $valor_acreditar) {
            $empleado = Empleado::where('empleado_id', $valor_acreditar->empleado_id)->first();
            $saldo_actual = floatval(self::obtenerSaldo($empleado->id, $acreditacion_semana->semana, $acreditacion_semana->created_at));
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
    /**
     * La función `obtenerSaldo` recupera el saldo actual de un empleado y número de semana específicos.
     *
     * @param int empleado_id El parámetro `empleado_id` es un número entero que representa el
     * identificador único de un empleado. Se utiliza para recuperar el saldo de un fondo de un empleado
     * específico.
     * @param string numero_semana El parámetro `numero_semana` representa el número de semana de la cual
     * se desea obtener el saldo. Esta función `obtenerSaldo` toma una identificación de empleado y un
     * número de semana como entrada, luego recupera el saldo de ese empleado hasta el inicio de la semana
     * especificada.
     *
     * @return La función `obtenerSaldo` devuelve el saldo actual de un fondo para un empleado específico
     * durante una semana determinada. Calcula el saldo consultando en la base de datos la entrada más
     * reciente en la tabla `Saldo` para el empleado que tiene una fecha en o antes del
     * inicio de la semana especificada por el parámetro de entrada ``. Si tal entrada
     */
    public static  function obtenerSaldo(int $empleado_id, string $numero_semana, $fecha_creacion)
    {
        $year = Carbon::parse($fecha_creacion)->year;
        $rango_fecha = self::obtenerRangoSemana($numero_semana, $year);
        $saldo_actual_historico = SaldoGrupo::where('id_usuario', $empleado_id)->where('fecha', '<=', $rango_fecha['startOfWeek'])->orderBy('id', 'desc')->first();
        $saldo_actual = is_null($saldo_actual_historico) ?  Saldo::where('empleado_id', $empleado_id)->where('fecha', '<=', $rango_fecha['startOfWeek'])->orderBy('id', 'desc')->first() : $saldo_actual_historico;
        $saldo_actual = !is_null($saldo_actual)  ? $saldo_actual->saldo_actual : 0;
        return $saldo_actual;
    }
    /**
     * Esta función PHP devuelve las fechas de inicio y finalización de un número de semana determinado.
     *
     * @param string numero_semana La función `obtenerRangoSemana` toma un parámetro de cadena
     * ``, que representa el número de semana para la cual se desea obtener el rango de
     * fechas. La función utiliza la biblioteca Carbon para calcular las fechas de inicio y finalización de
     * la semana especificada.
     *
     * @return La función `obtenerRangoSemana` devuelve una matriz con dos claves: `startOfWeek` y
     * `endOfWeek`, cada una de las cuales contiene una cadena de fecha formateada que representa el inicio
     * y el final de la semana correspondiente al número de semana dado.
     */
    public static  function obtenerRangoSemana(string $numero_semana, $year)
    {
        // Crear una instancia de Carbon con el primer día del año dado
        $startOfYear = Carbon::createFromDate($year, 1, 1);
        // Ajustar la fecha al primer día de la semana correspondiente al número de semana dado
        $startOfWeek = $startOfYear->addWeek($numero_semana - 1)->startOfWeek();
        // Ajustar la fecha al último día de la semana correspondiente al número de semana dado
        $endOfWeek = $startOfWeek->copy()->endOfWeek();
        $startOfWeek = $startOfWeek->format('Y-m-d');
        $endOfWeek = $endOfWeek->format('Y-m-d');
        return compact('startOfWeek', 'endOfWeek');
    }
}
