<?php

namespace App\Models\Medico;

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
 * App\Models\Medico\ExamenOrganoReproductivo
 *
 * @property int $id
 * @property string $examen
 * @property string $tipo
 * @property int $activo
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @method static Builder|ExamenOrganoReproductivo acceptRequest(?array $request = null)
 * @method static Builder|ExamenOrganoReproductivo filter(?array $request = null)
 * @method static Builder|ExamenOrganoReproductivo ignoreRequest(?array $request = null)
 * @method static Builder|ExamenOrganoReproductivo newModelQuery()
 * @method static Builder|ExamenOrganoReproductivo newQuery()
 * @method static Builder|ExamenOrganoReproductivo query()
 * @method static Builder|ExamenOrganoReproductivo setBlackListDetection(?array $black_list_detections = null)
 * @method static Builder|ExamenOrganoReproductivo setCustomDetection(?array $object_custom_detect = null)
 * @method static Builder|ExamenOrganoReproductivo setLoadInjectedDetection($load_default_detection)
 * @method static Builder|ExamenOrganoReproductivo whereActivo($value)
 * @method static Builder|ExamenOrganoReproductivo whereCreatedAt($value)
 * @method static Builder|ExamenOrganoReproductivo whereExamen($value)
 * @method static Builder|ExamenOrganoReproductivo whereId($value)
 * @method static Builder|ExamenOrganoReproductivo whereTipo($value)
 * @method static Builder|ExamenOrganoReproductivo whereUpdatedAt($value)
 * @mixin Eloquent
 */
class ExamenOrganoReproductivo extends Model implements Auditable
{
    use HasFactory;
    use HasFactory, UppercaseValuesTrait, AuditableModel, Filterable;

    protected $table = 'med_examenes_organos_reproductivos';
    protected $fillable = [
        'examen',
        'tipo', //M-F
        'activo', //boolean
    ];
    private static array $whiteListFilter = ['*'];
}
