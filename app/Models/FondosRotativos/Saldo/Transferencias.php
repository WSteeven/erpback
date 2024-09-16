<?php

namespace App\Models\FondosRotativos\Saldo;

use App\Models\Empleado;
use App\Models\FondosRotativos\Gasto\EstadoViatico;
use App\Models\Notificacion;
use App\Models\Tarea;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

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
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read Empleado|null $empleadoEnvia
 * @property-read Empleado|null $empleadoRecibe
 * @property-read EstadoViatico|null $estadoViatico
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Notificacion> $notificaciones
 * @property-read int|null $notificaciones_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\FondosRotativos\Saldo\Saldo> $saldoFondoRotativo
 * @property-read int|null $saldo_fondo_rotativo_count
 * @property-read Tarea|null $tarea
 * @method static \Illuminate\Database\Eloquent\Builder|Transferencias acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Transferencias filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Transferencias ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Transferencias newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Transferencias newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Transferencias query()
 * @method static \Illuminate\Database\Eloquent\Builder|Transferencias setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Transferencias setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Transferencias setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|Transferencias whereComprobante($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transferencias whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transferencias whereCuenta($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transferencias whereEsDevolucion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transferencias whereEstado($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transferencias whereFecha($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transferencias whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transferencias whereIdTarea($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transferencias whereMonto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transferencias whereMotivo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transferencias whereObservacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transferencias whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transferencias whereUsuarioEnviaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transferencias whereUsuarioRecibeId($value)
 * @mixin \Eloquent
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
    private static $whiteListFilter = [
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
