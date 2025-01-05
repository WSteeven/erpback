<?php

namespace App\Models;

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
 * App\Models\EstadoCivil
 *
 * @property int $id
 * @property string $nombre
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @method static Builder|EstadoCivil acceptRequest(?array $request = null)
 * @method static Builder|EstadoCivil filter(?array $request = null)
 * @method static Builder|EstadoCivil ignoreRequest(?array $request = null)
 * @method static Builder|EstadoCivil newModelQuery()
 * @method static Builder|EstadoCivil newQuery()
 * @method static Builder|EstadoCivil query()
 * @method static Builder|EstadoCivil setBlackListDetection(?array $black_list_detections = null)
 * @method static Builder|EstadoCivil setCustomDetection(?array $object_custom_detect = null)
 * @method static Builder|EstadoCivil setLoadInjectedDetection($load_default_detection)
 * @method static Builder|EstadoCivil whereCreatedAt($value)
 * @method static Builder|EstadoCivil whereId($value)
 * @method static Builder|EstadoCivil whereNombre($value)
 * @method static Builder|EstadoCivil whereUpdatedAt($value)
 * @mixin Eloquent
 */
class EstadoCivil extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;
    protected $table = 'estado_civil';
    protected $fillable = [
        'nombre'
    ];

    private static array $whiteListFilter = [
        'id',
        'nombre',
    ];
}
