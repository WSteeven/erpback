<?php

namespace App\Models\Medico;

use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

/**
 * App\Models\Medico\ExamenOrganoReproductivo
 *
 * @property int $id
 * @property string $examen
 * @property string $tipo
 * @property int $activo
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @method static \Illuminate\Database\Eloquent\Builder|ExamenOrganoReproductivo acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ExamenOrganoReproductivo filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ExamenOrganoReproductivo ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ExamenOrganoReproductivo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ExamenOrganoReproductivo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ExamenOrganoReproductivo query()
 * @method static \Illuminate\Database\Eloquent\Builder|ExamenOrganoReproductivo setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ExamenOrganoReproductivo setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ExamenOrganoReproductivo setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|ExamenOrganoReproductivo whereActivo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExamenOrganoReproductivo whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExamenOrganoReproductivo whereExamen($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExamenOrganoReproductivo whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExamenOrganoReproductivo whereTipo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExamenOrganoReproductivo whereUpdatedAt($value)
 * @mixin \Eloquent
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
    private static $whiteListFilter = ['*'];
}
