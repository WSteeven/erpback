<?php

namespace App\Models\FondosRotativos\Saldo;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Support\Carbon;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Models\Audit;

/**
 * App\Models\FondosRotativos\Saldo\EstadoAcreditaciones
 *
 * @property int $id
 * @property string $estado
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @method static Builder|EstadoAcreditaciones acceptRequest(?array $request = null)
 * @method static Builder|EstadoAcreditaciones filter(?array $request = null)
 * @method static Builder|EstadoAcreditaciones ignoreRequest(?array $request = null)
 * @method static Builder|EstadoAcreditaciones newModelQuery()
 * @method static Builder|EstadoAcreditaciones newQuery()
 * @method static Builder|EstadoAcreditaciones query()
 * @method static Builder|EstadoAcreditaciones setBlackListDetection(?array $black_list_detections = null)
 * @method static Builder|EstadoAcreditaciones setCustomDetection(?array $object_custom_detect = null)
 * @method static Builder|EstadoAcreditaciones setLoadInjectedDetection($load_default_detection)
 * @method static Builder|EstadoAcreditaciones whereCreatedAt($value)
 * @method static Builder|EstadoAcreditaciones whereEstado($value)
 * @method static Builder|EstadoAcreditaciones whereId($value)
 * @method static Builder|EstadoAcreditaciones whereUpdatedAt($value)
 * @mixin Eloquent
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
    private static array $whiteListFilter = [
        'estado',
    ];
}
