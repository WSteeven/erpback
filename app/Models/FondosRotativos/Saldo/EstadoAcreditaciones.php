<?php

namespace App\Models\FondosRotativos\Saldo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

/**
 * App\Models\FondosRotativos\Saldo\EstadoAcreditaciones
 *
 * @property int $id
 * @property string $estado
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @method static \Illuminate\Database\Eloquent\Builder|EstadoAcreditaciones acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|EstadoAcreditaciones filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|EstadoAcreditaciones ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|EstadoAcreditaciones newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EstadoAcreditaciones newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EstadoAcreditaciones query()
 * @method static \Illuminate\Database\Eloquent\Builder|EstadoAcreditaciones setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|EstadoAcreditaciones setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|EstadoAcreditaciones setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|EstadoAcreditaciones whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EstadoAcreditaciones whereEstado($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EstadoAcreditaciones whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EstadoAcreditaciones whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class EstadoAcreditaciones extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;
    const REALIZADO = 1;
    const ANULADO = 2;
    const MIGRACION = 3;

    protected $table = 'estado_acreditaciones';
    protected $primaryKey = 'id';
    protected $fillable = [
        'estado',
    ];
    private static $whiteListFilter = [
        'estado',
    ];
}
