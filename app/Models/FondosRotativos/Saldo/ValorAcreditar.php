<?php

namespace App\Models\FondosRotativos\Saldo;

use App\Models\Empleado;
use App\Models\FondosRotativos\UmbralFondosRotativos;
use App\Traits\UppercaseValuesTrait;
use Carbon\Carbon;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class ValorAcreditar extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;
    use UppercaseValuesTrait;
    protected $table = 'fr_valor_acreditar';
    protected $primaryKey = 'id';
    protected $fillable = [
        'empleado_id',
        'acreditacion_semana_id',
        'monto_generado',
        'monto_modificado',
        'motivo',
        'estado',
    ];
    private static $whiteListFilter = [
        'id',
        'empleado_id',
        'acreditacion_semana_id',
        'monto_generado',
        'monto_modificado',
        'estado',

    ];
    protected $casts = [
        'estado' => 'boolean',
    ];
    public function empleado()
    {
        return $this->hasOne(Empleado::class, 'id', 'empleado_id')->with('canton');
    }
    public function acreditacion_semanal()
    {
        return $this->hasOne(AcreditacionSemana::class, 'id', 'acreditacion_semana_id');
    }
    public function umbral()
    {
        return $this->hasOne(UmbralFondosRotativos::class, 'empleado_id', 'empleado_id');
    }
    public static function empaquetarCash($valores_acreditar)
    {
        $results = [];
        $id = 0;
        $row = [];

        foreach ($valores_acreditar as $valor_acreditar) {
            $cuenta_bancarea_num = intval($valor_acreditar->empleado->num_cuenta_bancaria);
            if ($cuenta_bancarea_num > 0) {
                $referencia = $valor_acreditar->umbral != null ? $valor_acreditar->umbral->referencia : 'FONDOS ROTATIVOS CAJA ' . $valor_acreditar->empleado->canton->canton;
                $row['item'] = $id + 1;
                $row['empleado_info'] =  $valor_acreditar->empleado->apellidos . ' ' . $valor_acreditar->empleado->nombres;
                $row['numero_cuenta_bancareo'] =  $valor_acreditar->empleado->num_cuenta_bancaria;
                $row['email'] =  $valor_acreditar->empleado->user->email;
                $row['tipo_pago'] = 'PA';
                $row['numero_cuenta_empresa'] = '02653010903';
                $row['moneda'] = 'USD';
                $row['forma_pago'] = 'CTA';
                $row['codigo_banco'] = '0036';
                $row['tipo_cuenta'] = 'AHO';
                $row['tipo_documento_empleado'] = 'C';
                $row['referencia'] = strtoupper($referencia);
                $row['identificacion'] =  $valor_acreditar->empleado->identificacion;
                $row['total'] = str_replace(".", "", number_format($valor_acreditar->monto_modificado, 2, ',', '.'));
                $results[$id] = $row;
                $id++;
            }
        }
        usort($results, __CLASS__ . "::ordenarNombresApellidos");

        return $results;
    }

    public static function empaquetar($valores_acreditar)
    {
        $results = [];
        $id = 0;
        $row = [];

        foreach ($valores_acreditar as $valor_acreditar) {
            $row['item'] = $id + 1;
            $row['empleado_info'] =  $valor_acreditar->empleado->apellidos . ' ' . $valor_acreditar->empleado->nombres;
            $row['monto_modificado'] = str_replace(".", "", number_format($valor_acreditar->monto_modificado, 2, ',', '.'));
            $row['monto_generado'] = str_replace(".", "", number_format($valor_acreditar->monto_generado, 2, ',', '.'));
            // Obtener la posición del primer carácter no numérico
            $numeroSemana = explode("FONDO ROTATIVO SEMANA #", $valor_acreditar->acreditacion_semanal->semana)[1];
            $row['saldo_actual'] = ValorAcreditar::obtener_saldo($valor_acreditar->empleado_id, $numeroSemana);
            $row['motivo'] = $valor_acreditar->motivo;
            $results[$id] = $row;
            $id++;
        }
        usort($results, __CLASS__ . "::ordenarNombresApellidos");

        return $results;
    }
    private static function  ordenarNombresApellidos($a, $b)
    {
        $nameA = $a['empleado_info'] . ' ' . $a['empleado_info'];
        $nameB = $b['empleado_info'] . ' ' . $b['empleado_info'];
        return strcmp($nameA, $nameB);
    }
    public static  function obtener_saldo($empleado_id, $numero_semana)
    {
        $rango_fecha = ValorAcreditar::obtenerRangoSemana($numero_semana);
        $saldo_actual = SaldoGrupo::where('id_usuario', $empleado_id)->where('fecha', '<=', $rango_fecha['startOfWeek'])->orderBy('id', 'desc')->first();
        $saldo_actual = $saldo_actual != null ? $saldo_actual->saldo_actual : 0;
        return $saldo_actual;
    }

    public static function obtenerRangoSemana($weekNumber)
    {
        $startOfWeek = Carbon::now()->startOfWeek($weekNumber)->format('Y-m-d');;
        $endOfWeek = Carbon::now()->endOfWeek($weekNumber)->format('Y-m-d');
        return compact('startOfWeek', 'endOfWeek');
    }
}
