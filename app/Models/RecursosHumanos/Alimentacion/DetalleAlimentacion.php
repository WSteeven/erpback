<?php

namespace App\Models\RecursosHumanos\Alimentacion;

use App\Models\Empleado;
use App\Models\RecursosHumanos\NominaPrestamos\RolPago;
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
 * App\Models\RecursosHumanos\Alimentacion\DetalleAlimentacion
 *
 * @property int $id
 * @property string $valor_asignado
 * @property string $fecha_corte
 * @property int $empleado_id
 * @property int $alimentacion_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Alimentacion|null $alimentacion
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @property-read Empleado|null $empleado
 * @method static Builder|DetalleAlimentacion acceptRequest(?array $request = null)
 * @method static Builder|DetalleAlimentacion filter(?array $request = null)
 * @method static Builder|DetalleAlimentacion ignoreRequest(?array $request = null)
 * @method static Builder|DetalleAlimentacion newModelQuery()
 * @method static Builder|DetalleAlimentacion newQuery()
 * @method static Builder|DetalleAlimentacion query()
 * @method static Builder|DetalleAlimentacion setBlackListDetection(?array $black_list_detections = null)
 * @method static Builder|DetalleAlimentacion setCustomDetection(?array $object_custom_detect = null)
 * @method static Builder|DetalleAlimentacion setLoadInjectedDetection($load_default_detection)
 * @method static Builder|DetalleAlimentacion whereAlimentacionId($value)
 * @method static Builder|DetalleAlimentacion whereCreatedAt($value)
 * @method static Builder|DetalleAlimentacion whereEmpleadoId($value)
 * @method static Builder|DetalleAlimentacion whereFechaCorte($value)
 * @method static Builder|DetalleAlimentacion whereId($value)
 * @method static Builder|DetalleAlimentacion whereUpdatedAt($value)
 * @method static Builder|DetalleAlimentacion whereValorAsignado($value)
 * @mixin Eloquent
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

    private static array $whiteListFilter = [
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

    public function alimentacion()
    {
        return $this->hasOne(Alimentacion::class, 'id', 'alimentacion_id');
    }

    public static function empaquetar($detalles_alimentacion)
    {
        $results = [];
        $id = 0;
        $row = [];

        foreach ($detalles_alimentacion as $detalle) {
            $row['item'] = $id + 1;
            $row['empleado'] = $detalle->empleado->apellidos . ' ' . $detalle->empleado->nombres;
            $row['valor_asignado'] = str_replace(".", "", number_format($detalle->valor_asignado, 2, ',', '.'));
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

        foreach ($detalles_alimentacion as $detalle) {
            $cuenta_bancarea_num = intval($detalle->empleado->num_cuenta_bancaria);
            if ($cuenta_bancarea_num > 0) {
                $referencia = $detalle->alimentacion->es_quincena ? 'ALIMENTACION PRIMERA QUINCENA MES ' : 'ALIMENTACION FIN DE MES ';
                $row = RolPago::getDatosBancariosDefault();
                $row['item'] = $id + 1;
                $row['empleado'] = $detalle->empleado->apellidos . ' ' . $detalle->empleado->nombres;
                $row['departamento'] = $detalle->empleado->departamento->nombre;
                $row['numero_cuenta_bancareo'] = $detalle->empleado->num_cuenta_bancaria;
                $row['email'] = $detalle->empleado->user->email;
                $row['referencia'] = strtoupper($referencia . ucfirst(Carbon::parse($detalle->alimentacion->mes)->locale('es')->translatedFormat('F')));
                $row['identificacion'] = $detalle->empleado->identificacion;
                $row['total'] = str_replace(".", "", number_format($detalle->valor_asignado, 2, ',', '.'));
                $results[$id] = $row;
                $id++;
            }
        }
        usort($results, __CLASS__ . "::ordenarNombresApellidos");

        return $results;
    }

    private static function ordenarNombresApellidos($a, $b)
    {
        $nameA = $a['empleado'] . ' ' . $a['empleado'];
        $nameB = $b['empleado'] . ' ' . $b['empleado'];
        return strcmp($nameA, $nameB);
    }
}
