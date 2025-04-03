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
 * App\Models\RecursosHumanos\NominaPrestamos\Periodo
 *
 * @property int $id
 * @property string $nombre
 * @property int $activo
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @method static Builder|Periodo acceptRequest(?array $request = null)
 * @method static Builder|Periodo filter(?array $request = null)
 * @method static Builder|Periodo ignoreRequest(?array $request = null)
 * @method static Builder|Periodo newModelQuery()
 * @method static Builder|Periodo newQuery()
 * @method static Builder|Periodo query()
 * @method static Builder|Periodo setBlackListDetection(?array $black_list_detections = null)
 * @method static Builder|Periodo setCustomDetection(?array $object_custom_detect = null)
 * @method static Builder|Periodo setLoadInjectedDetection($load_default_detection)
 * @method static Builder|Periodo whereActivo($value)
 * @method static Builder|Periodo whereCreatedAt($value)
 * @method static Builder|Periodo whereId($value)
 * @method static Builder|Periodo whereNombre($value)
 * @method static Builder|Periodo whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Periodo extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;
    protected $table = 'periodos';
    protected $fillable = [
        'nombre',
        'activo'
    ];

    private static array $whiteListFilter = [
        'id',
        'nombre',
        'activo'
    ];
}
