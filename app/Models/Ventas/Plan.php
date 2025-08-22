<?php

namespace App\Models\Ventas;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use OwenIt\Auditing\Models\Audit;

/**
 * App\Models\Ventas\Plan
 *
 * @property int $id
 * @property string $nombre
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @method static Builder|Plan acceptRequest(?array $request = null)
 * @method static Builder|Plan filter(?array $request = null)
 * @method static Builder|Plan ignoreRequest(?array $request = null)
 * @method static Builder|Plan newModelQuery()
 * @method static Builder|Plan newQuery()
 * @method static Builder|Plan query()
 * @method static Builder|Plan setBlackListDetection(?array $black_list_detections = null)
 * @method static Builder|Plan setCustomDetection(?array $object_custom_detect = null)
 * @method static Builder|Plan setLoadInjectedDetection($load_default_detection)
 * @method static Builder|Plan whereCreatedAt($value)
 * @method static Builder|Plan whereId($value)
 * @method static Builder|Plan whereNombre($value)
 * @method static Builder|Plan whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Plan extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel, UppercaseValuesTrait, Filterable;
    protected $table = 'ventas_planes';
    protected $fillable =['nombre'];
    private static array $whiteListFilter = [
        '*',
    ];

}
