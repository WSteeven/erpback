<?php

namespace App\Models\RecursosHumanos\Alimentacion;

use App\Models\Empleado;
use Carbon\Carbon;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

/**
 * App\Models\RecursosHumanos\Alimentacion\DetalleAlimentacion
 *
 * @property int $id
 * @property string $valor_asignado
 * @property string $fecha_corte
 * @property int $empleado_id
 * @property int $alimentacion_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\RecursosHumanos\Alimentacion\Alimentacion|null $alimentacion
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read Empleado|null $empleado
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleAlimentacion acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleAlimentacion filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleAlimentacion ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleAlimentacion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleAlimentacion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleAlimentacion query()
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleAlimentacion setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleAlimentacion setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleAlimentacion setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleAlimentacion whereAlimentacionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleAlimentacion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleAlimentacion whereEmpleadoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleAlimentacion whereFechaCorte($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleAlimentacion whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleAlimentacion whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleAlimentacion whereValorAsignado($value)
 * @mixin \Eloquent
 */
class DetalleAlimentacion extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;
    protected $table = 'rrhh_detalle_alimentaciones';
    protected $fillable = [
        'empleado_id',
        'valor_asignado',
        'fecha_corte',
        'alimentacion_id'
    ];

    private static $whiteListFilter = [
        'empleado_id',
        'empleado',
        'valor_asignado',
        'fecha_corte',
        'alimentacion',
        'alimentacion_id'
    ];
    public function empleado()
    {
        return $this->hasOne(Empleado::class, 'id', 'empleado_id');
    }
    public function alimentacion(){
        return $this->hasOne(Alimentacion::class, 'id', 'alimentacion_id');
    }

    public static function empaquetar($detalles_alimentacion)
    {
        $results = [];
        $id = 0;
        $row = [];

        foreach ($detalles_alimentacion as $detalles_alimentacion) {
            $row['item'] = $id + 1;
            $row['empleado'] = $detalles_alimentacion->empleado->apellidos . ' ' . $detalles_alimentacion->empleado->nombres;
            $row['valor_asignado'] = str_replace(".", "", number_format($detalles_alimentacion->valor_asignado, 2, ',', '.'));
            $results[$id] = $row;
            $id++;
        }
        usort($results, __CLASS__ . "::ordenarNombresApellidos");

        return $results;
    }
    public static function empaquetarCash($detalles_alimentacion)
    {
        $results = [];
        $id = 0;
        $row = [];

        foreach ($detalles_alimentacion as $detalles_alimentacion) {
            $cuenta_bancarea_num = intval($detalles_alimentacion->empleado->num_cuenta_bancaria);
            if ($cuenta_bancarea_num > 0) {
                $referencia = $detalles_alimentacion->alimentacion->es_quincena ? 'ALIMENTACION PRIMERA QUINCENA MES ' : 'ALIMENTACION FIN DE MES ';
                $row['item'] = $id + 1;
                $row['empleado'] =  $detalles_alimentacion->empleado->apellidos . ' ' . $detalles_alimentacion->empleado->nombres;
                $row['departamento'] = $detalles_alimentacion->empleado->departamento->nombre;
                $row['numero_cuenta_bancareo'] =  $detalles_alimentacion->empleado->num_cuenta_bancaria;
                $row['email'] =  $detalles_alimentacion->empleado->user->email;
                $row['tipo_pago'] = 'PA';
                $row['numero_cuenta_empresa'] = '02653010903';
                $row['moneda'] = 'USD';
                $row['forma_pago'] = 'CTA';
                $row['codigo_banco'] = '0036';
                $row['tipo_cuenta'] = 'AHO';
                $row['tipo_documento_empleado'] = 'C';
                $row['referencia'] = strtoupper($referencia . ucfirst(Carbon::parse($detalles_alimentacion->alimentacion->mes)->locale('es')->translatedFormat('F')));
                $row['identificacion'] =  $detalles_alimentacion->empleado->identificacion;
                $row['total'] =  str_replace(".", "", number_format($detalles_alimentacion->valor_asignado, 2, ',', '.'));
                $results[$id] = $row;
                $id++;
            }
        }
        usort($results, __CLASS__ . "::ordenarNombresApellidos");

        return $results;
    }
    private static function  ordenarNombresApellidos($a, $b)
    {
        $nameA = $a['empleado'] . ' ' . $a['empleado'];
        $nameB = $b['empleado'] . ' ' . $b['empleado'];
        return strcmp($nameA, $nameB);
    }
}
