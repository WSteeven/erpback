<?php

namespace App\Http\Resources\FondosRotativos\Saldo;

use App\Models\FondosRotativos\Saldo\SaldoGrupo;
use App\Models\FondosRotativos\Saldo\Saldo;
use App\Models\FondosRotativos\Saldo\ValorAcreditar;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;

class ValorAcreditarResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $controller_method = $request->route()->getActionMethod();
        $numeroSemana = explode("FONDO ROTATIVO SEMANA #", $this->acreditacion_semanal->semana)[1];

        $modelo = [
            'id' => $this->id,
            'empleado_info' => $this->empleado->nombres != null ? $this->empleado->apellidos . ' ' . $this->empleado->nombres : '',
            'empleado' => $this->empleado_id,
            'umbral_empleado' => $this->empleado->umbral != null ? number_format($this->empleado->umbral->valor_minimo, 2) : 0,
            'saldo_empleado' => $this->obtenerSaldo($this->empleado_id, $numeroSemana),
            'monto_generado' => number_format($this->monto_generado, 2),
            'monto_modificado' => str_replace(",", "", number_format($this->monto_modificado, 2)),
            'acreditacion_semana' => $this->acreditacion_semana_id,
            'es_acreditado' => $this->acreditacion_semanal != null ? $this->acreditacion_semanal->acreditar : '',
            'acreditacion_semana_info' => $this->acreditacion_semanal != null ? $this->acreditacion_semanal->semana : '',
            'motivo' => $this->motivo,
            'estado' => $this->estado
        ];
        return $modelo;
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
    public function obtenerSaldo(int $empleado_id, string $numero_semana)
    {
        $rango_fecha = $this->obtenerRangoSemana($numero_semana);
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
    public  function obtenerRangoSemana(string $numero_semana)
    {
        $startOfWeek = Carbon::now()->startOfWeek($numero_semana)->format('Y-m-d');;
        $endOfWeek = Carbon::now()->endOfWeek($numero_semana)->format('Y-m-d');
        return compact('startOfWeek', 'endOfWeek');
    }
}
