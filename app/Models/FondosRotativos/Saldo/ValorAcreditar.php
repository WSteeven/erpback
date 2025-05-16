<?php

namespace App\Models\FondosRotativos\Saldo;

use App\Models\Empleado;
use App\Models\FondosRotativos\UmbralFondosRotativos;
use App\Models\RecursosHumanos\NominaPrestamos\RolPago;
use App\Traits\UppercaseValuesTrait;
use Carbon\Carbon;
use Eloquent;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Models\Audit;

/**
 * App\Models\FondosRotativos\Saldo\ValorAcreditar
 *
 * @property int $id
 * @property int $empleado_id
 * @property int $acreditacion_semana_id
 * @property string $monto_generado
 * @property string $monto_modificado
 * @property float $saldo_empleado
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $motivo
 * @property bool $estado
 * @property-read AcreditacionSemana|null $acreditacion_semanal
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @property-read Empleado|null $empleado
 * @property-read UmbralFondosRotativos|null $umbral
 * @method static Builder|ValorAcreditar acceptRequest(?array $request = null)
 * @method static Builder|ValorAcreditar filter(?array $request = null)
 * @method static Builder|ValorAcreditar ignoreRequest(?array $request = null)
 * @method static Builder|ValorAcreditar newModelQuery()
 * @method static Builder|ValorAcreditar newQuery()
 * @method static Builder|ValorAcreditar query()
 * @method static Builder|ValorAcreditar setBlackListDetection(?array $black_list_detections = null)
 * @method static Builder|ValorAcreditar setCustomDetection(?array $object_custom_detect = null)
 * @method static Builder|ValorAcreditar setLoadInjectedDetection($load_default_detection)
 * @method static Builder|ValorAcreditar whereAcreditacionSemanaId($value)
 * @method static Builder|ValorAcreditar whereCreatedAt($value)
 * @method static Builder|ValorAcreditar whereEmpleadoId($value)
 * @method static Builder|ValorAcreditar whereEstado($value)
 * @method static Builder|ValorAcreditar whereId($value)
 * @method static Builder|ValorAcreditar whereMontoGenerado($value)
 * @method static Builder|ValorAcreditar whereMontoModificado($value)
 * @method static Builder|ValorAcreditar whereMotivo($value)
 * @method static Builder|ValorAcreditar whereSaldoEmpleado($value)
 * @method static Builder|ValorAcreditar whereUpdatedAt($value)
 * @mixin Eloquent
 */
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
        'saldo_empleado'
    ];
    private static array $whiteListFilter = [
        'id',
        'empleado_id',
        'acreditacion_semana_id',
        'monto_generado',
        'monto_modificado',
        'estado',
        'saldo_empleado'
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

        foreach ($valores_acreditar as $valor_acreditar) {
            $cuenta_bancarea_num = intval($valor_acreditar->empleado->num_cuenta_bancaria);
            if ($cuenta_bancarea_num > 0) {
                $referencia = $valor_acreditar->umbral != null ? $valor_acreditar->umbral->referencia : 'FONDOS ROTATIVOS CAJA ' . $valor_acreditar->empleado->canton->canton;
                $row = RolPago::getDatosBancariosDefault();
                $row['item'] = $id + 1;
                $row['empleado_info'] =  $valor_acreditar->empleado->apellidos . ' ' . $valor_acreditar->empleado->nombres;
                $row['numero_cuenta_bancareo'] =  $valor_acreditar->empleado->num_cuenta_bancaria;
                $row['email'] =  $valor_acreditar->empleado->user->email;

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
        return $saldo_actual != null ? $saldo_actual->saldo_actual : 0;
    }

    public static function obtenerRangoSemana($weekNumber)
    {
        $startOfWeek = Carbon::now()->startOfWeek($weekNumber)->format('Y-m-d');
        $endOfWeek = Carbon::now()->endOfWeek($weekNumber)->format('Y-m-d');
        return compact('startOfWeek', 'endOfWeek');
    }
}
