<?php

namespace App\Models\FondosRotativos\Saldo;

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
 * App\Models\FondosRotativos\Saldo\TipoSaldo
 *
 * @property int $id
 * @property string $descripcion
 * @property int $id_estatus
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @method static Builder|TipoSaldo acceptRequest(?array $request = null)
 * @method static Builder|TipoSaldo filter(?array $request = null)
 * @method static Builder|TipoSaldo ignoreRequest(?array $request = null)
 * @method static Builder|TipoSaldo newModelQuery()
 * @method static Builder|TipoSaldo newQuery()
 * @method static Builder|TipoSaldo query()
 * @method static Builder|TipoSaldo setBlackListDetection(?array $black_list_detections = null)
 * @method static Builder|TipoSaldo setCustomDetection(?array $object_custom_detect = null)
 * @method static Builder|TipoSaldo setLoadInjectedDetection($load_default_detection)
 * @method static Builder|TipoSaldo whereCreatedAt($value)
 * @method static Builder|TipoSaldo whereDescripcion($value)
 * @method static Builder|TipoSaldo whereId($value)
 * @method static Builder|TipoSaldo whereIdEstatus($value)
 * @method static Builder|TipoSaldo whereUpdatedAt($value)
 * @mixin Eloquent
 */
class TipoSaldo extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;
    protected $table = 'tipo_saldo';
    protected $primaryKey = 'id';
    protected $fillable = [
        'descripcion',
        'transcriptor',
        'fecha_trans',
    ];
    private static array $whiteListFilter = [
        'descripcion',
    ];
}
