<?php

namespace App\Models\FondosRotativos\Gasto;

use App\Models\Canton;
use App\Models\Empleado;
use App\Models\EstadoTransaccion;
use App\Models\Grupo;
use App\Models\Notificacion;
use App\Traits\UppercaseValuesTrait;
use Eloquent;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Models\Audit;
use Throwable;

/**
 * App\Models\FondosRotativos\Gasto\GastoCoordinador
 *
 * @property int $id
 * @property string $fecha_gasto
 * @property int $id_lugar
 * @property float $monto
 * @property string $observacion
 * @property int $id_usuario
 * @property int $id_grupo
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @property-read Canton|null $canton
 * @property-read Collection<int, MotivoGasto> $detalleMotivoGasto
 * @property-read int|null $detalle_motivo_gasto_count
 * @property-read Empleado|null $empleado
 * @property-read Grupo|null $grupo
 * @property-read MotivoGasto|null $motivoGasto
 * @property-read Collection<int, Notificacion> $notificaciones
 * @property-read int|null $notificaciones_count
 * @method static Builder|GastoCoordinador acceptRequest(?array $request = null)
 * @method static Builder|GastoCoordinador filter(?array $request = null)
 * @method static Builder|GastoCoordinador ignoreRequest(?array $request = null)
 * @method static Builder|GastoCoordinador newModelQuery()
 * @method static Builder|GastoCoordinador newQuery()
 * @method static Builder|GastoCoordinador query()
 * @method static Builder|GastoCoordinador setBlackListDetection(?array $black_list_detections = null)
 * @method static Builder|GastoCoordinador setCustomDetection(?array $object_custom_detect = null)
 * @method static Builder|GastoCoordinador setLoadInjectedDetection($load_default_detection)
 * @method static Builder|GastoCoordinador whereCreatedAt($value)
 * @method static Builder|GastoCoordinador whereFechaGasto($value)
 * @method static Builder|GastoCoordinador whereId($value)
 * @method static Builder|GastoCoordinador whereIdGrupo($value)
 * @method static Builder|GastoCoordinador whereIdLugar($value)
 * @method static Builder|GastoCoordinador whereIdUsuario($value)
 * @method static Builder|GastoCoordinador whereMonto($value)
 * @method static Builder|GastoCoordinador whereObservacion($value)
 * @method static Builder|GastoCoordinador whereUpdatedAt($value)
 * @mixin Eloquent
 */
class GastoCoordinador extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;
    use UppercaseValuesTrait;

    protected $table = 'gastos_coordinador';
    protected $primaryKey = 'id';
    protected $fillable = [
        'fecha_gasto',
        'id_lugar',
        'id_grupo',
        'monto',
        'observacion',
        'id_usuario',
        'revisado',
        'estado_id'
    ];

    protected $casts = [
        'revisado'=>'boolean'
    ];
    private static array $whiteListFilter = ['*'];
//        'fecha_gasto',
//        'lugar',
//        'grupo',
//        'monto',
//        'observacion',
//        'id_usuario',
//    ];

    public function estado()
    {
        return $this->belongsTo(EstadoTransaccion::class);
    }

    public function motivoGasto()
    {
        return $this->hasOne(MotivoGasto::class, 'id', 'id_motivo');
    }

    public function empleado()
    {
        return $this->hasOne(Empleado::class, 'id', 'id_usuario')->with('user');
    }

    public function grupo()
    {
        return $this->hasOne(Grupo::class, 'id', 'id_grupo');
    }

    public function canton()
    {
        return $this->hasOne(Canton::class, 'id', 'id_lugar');
    }

    public function detalleMotivoGasto()
    {
        return $this->belongsToMany(MotivoGasto::class, 'detalle_motivo_gastos', 'id_gasto_coordinador', 'id_motivo_gasto');
    }

    /**
     * @throws Throwable
     */
    public static function empaquetar($gastos)
    {
        $results = [];
        $id = 0;
        $row = [];
        foreach ($gastos as $gasto) {
            $row['fecha_gasto'] = $gasto->fecha_gasto;
            $row['lugar'] = $gasto->id_lugar;
            $row['grupo'] = $gasto->id_grupo;
            $row['grupo_info'] = $gasto->grupo->nombre;
            $row['motivo_info'] = self::obtenerNombresMotivos($gasto->detalleMotivoGasto);
            $row['motivo'] = $gasto->motivoGasto != null ? $gasto->motivoGasto->pluck('id') : null;
            $row['lugar_info'] = $gasto?->canton->canton;
            $row['monto'] = $gasto->monto;
            $row['observacion'] = $gasto->observacion;
            $row['usuario'] = $gasto->id_usuario;
            $row['empleado_info'] = $gasto->empleado != null ? $gasto?->empleado?->nombres . ' ' . $gasto?->empleado?->apellidos : ' ';
            $results[$id] = $row;
            $id++;
        }

        return $results;
    }

    private static function obtenerNombresMotivos($motivos)
    {
        $nombres = array();
        if (!is_null($motivos)) {
            foreach ($motivos as $motivo) {
                $nombres[] = $motivo['nombre'];
            }
            return implode(", ", $nombres);
        } else {
            return '';
        }
    }

    public function notificaciones()
    {
        return $this->morphMany(Notificacion::class, 'notificable');
    }
}
