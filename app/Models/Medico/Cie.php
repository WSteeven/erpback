<?php

namespace App\Models\Medico;

use App\Traits\UppercaseValuesTrait;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use OwenIt\Auditing\Models\Audit;

/**
 * App\Models\Medico\Cie
 *
 * @property int $id
 * @property string $codigo
 * @property string $nombre_enfermedad
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @method static Builder|Cie acceptRequest(?array $request = null)
 * @method static Builder|Cie filter(?array $request = null)
 * @method static Builder|Cie ignoreRequest(?array $request = null)
 * @method static Builder|Cie newModelQuery()
 * @method static Builder|Cie newQuery()
 * @method static Builder|Cie query()
 * @method static Builder|Cie setBlackListDetection(?array $black_list_detections = null)
 * @method static Builder|Cie setCustomDetection(?array $object_custom_detect = null)
 * @method static Builder|Cie setLoadInjectedDetection($load_default_detection)
 * @method static Builder|Cie whereCodigo($value)
 * @method static Builder|Cie whereCreatedAt($value)
 * @method static Builder|Cie whereId($value)
 * @method static Builder|Cie whereNombreEnfermedad($value)
 * @method static Builder|Cie whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Cie extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, Filterable, AuditableModel;

    protected $table = 'med_cies';
    protected $fillable = [
        'codigo',
        'nombre_enfermedad',
    ];

    private static array $whiteListFilter = ['*'];
}
