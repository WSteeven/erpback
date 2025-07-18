<?php

namespace App\Models\FondosRotativos\Saldo;

use App\Models\Empleado;
use App\Models\FondosRotativos\Gasto\EstadoViatico;
use App\Models\Notificacion;
use App\Models\Tarea;
use App\Traits\UppercaseValuesTrait;
use Eloquent;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Models\Audit;

/**
 * App\Models\FondosRotativos\Saldo\Transferencias
 *
 * @method static where(string $string, int $empleado_id)
 * @property int $id
 * @property int $usuario_envia_id
 * @property int|null $usuario_recibe_id
 * @property int $estado
 * @property string $monto
 * @property string $motivo
 * @property string|null $observacion
 * @property string $cuenta
 * @property string $comprobante
 * @property int|null $id_tarea
 * @property string|null $fecha
 * @property bool $es_devolucion
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @property-read Empleado|null $empleadoEnvia
 * @property-read Empleado|null $empleadoRecibe
 * @property-read EstadoViatico|null $estadoViatico
 * @property-read Collection<int, Notificacion> $notificaciones
 * @property-read int|null $notificaciones_count
 * @property-read Collection<int, Saldo> $saldoFondoRotativo
 * @property-read int|null $saldo_fondo_rotativo_count
 * @property-read Tarea|null $tarea
 * @method static Builder|Transferencias acceptRequest(?array $request = null)
 * @method static Builder|Transferencias filter(?array $request = null)
 * @method static Builder|Transferencias ignoreRequest(?array $request = null)
 * @method static Builder|Transferencias newModelQuery()
 * @method static Builder|Transferencias newQuery()
 * @method static Builder|Transferencias query()
 * @method static Builder|Transferencias setBlackListDetection(?array $black_list_detections = null)
 * @method static Builder|Transferencias setCustomDetection(?array $object_custom_detect = null)
 * @method static Builder|Transferencias setLoadInjectedDetection($load_default_detection)
 * @method static Builder|Transferencias whereComprobante($value)
 * @method static Builder|Transferencias whereCreatedAt($value)
 * @method static Builder|Transferencias whereCuenta($value)
 * @method static Builder|Transferencias whereEsDevolucion($value)
 * @method static Builder|Transferencias whereEstado($value)
 * @method static Builder|Transferencias whereFecha($value)
 * @method static Builder|Transferencias whereId($value)
 * @method static Builder|Transferencias whereIdTarea($value)
 * @method static Builder|Transferencias whereMonto($value)
 * @method static Builder|Transferencias whereMotivo($value)
 * @method static Builder|Transferencias whereObservacion($value)
 * @method static Builder|Transferencias whereUpdatedAt($value)
 * @method static Builder|Transferencias whereUsuarioEnviaId($value)
 * @method static Builder|Transferencias whereUsuarioRecibeId($value)
 * @mixin Eloquent
 */
class Transferencias extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;
    use UppercaseValuesTrait;
    protected $table = 'transferencias_saldos';
    protected $primaryKey = 'id';

    protected $fillable = [
        'usuario_envia_id',
        'usuario_recibe_id',
        'monto',
        'motivo',
        'cuenta',
        'observacion',
        'id_tarea',
        'estado',
        'comprobante',
        'fecha',
        'es_devolucion'
    ];
    public const APROBADO = 1;
    public const RECHAZADO = 2;
    public const PENDIENTE = 3;
    public const ANULADO = 4;

    protected $casts = [
        'es_devolucion' => 'boolean',
    ];

    public function empleadoEnvia()
    {
        return $this->belongsTo(Empleado::class, 'usuario_envia_id');
    }
    public function estadoViatico()
    {
        return $this->hasOne(EstadoViatico::class, 'id','estado');
    }
    public function tarea()
    {
        return $this->hasOne(Tarea::class, 'id','id_tarea');
    }
    public function empleadoRecibe()
    {
        return $this->belongsTo(Empleado::class, 'usuario_recibe_id');
    }
    public function notificaciones()
    {
        return $this->morphMany(Notificacion::class, 'notificable');
    }
    public function saldoFondoRotativo()
    {
        return $this->morphMany(Saldo::class, 'saldoable');
    }
    private static array $whiteListFilter = [
        'usuario_envia_id',
        'usuario_recibe_id',
        'monto',
        'motivo',
        'cuenta',
        'observacion',
        'id_tarea',
        'estado',
        'comprobante',
        'fecha'
    ];
}
