<?php

namespace App\Models\RecursosHumanos\NominaPrestamos;


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
 * App\Models\RecursosHumanos\NominaPrestamos\HorasExtraTipo
 *
 * @property int $id
 * @property string $nombre
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @method static Builder|HorasExtraTipo acceptRequest(?array $request = null)
 * @method static Builder|HorasExtraTipo filter(?array $request = null)
 * @method static Builder|HorasExtraTipo ignoreRequest(?array $request = null)
 * @method static Builder|HorasExtraTipo newModelQuery()
 * @method static Builder|HorasExtraTipo newQuery()
 * @method static Builder|HorasExtraTipo query()
 * @method static Builder|HorasExtraTipo setBlackListDetection(?array $black_list_detections = null)
 * @method static Builder|HorasExtraTipo setCustomDetection(?array $object_custom_detect = null)
 * @method static Builder|HorasExtraTipo setLoadInjectedDetection($load_default_detection)
 * @method static Builder|HorasExtraTipo whereCreatedAt($value)
 * @method static Builder|HorasExtraTipo whereId($value)
 * @method static Builder|HorasExtraTipo whereNombre($value)
 * @method static Builder|HorasExtraTipo whereUpdatedAt($value)
 * @mixin Eloquent
 */
class HorasExtraTipo extends Model  implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;
    protected $table = 'horas_extra_tipos';
    protected $fillable = [
        'nombre'
    ];

    private static array $whiteListFilter = [
        'id',
        'nombre',
    ];
}
