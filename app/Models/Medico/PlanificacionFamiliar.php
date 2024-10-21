<?php

namespace App\Models\Medico;

use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;


/**
 * App\Models\Medico\PlanificacionFamiliar
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @method static \Illuminate\Database\Eloquent\Builder|PlanificacionFamiliar acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanificacionFamiliar filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanificacionFamiliar ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanificacionFamiliar newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PlanificacionFamiliar newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PlanificacionFamiliar query()
 * @method static \Illuminate\Database\Eloquent\Builder|PlanificacionFamiliar setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanificacionFamiliar setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanificacionFamiliar setLoadInjectedDetection($load_default_detection)
 * @mixin \Eloquent
 */
class PlanificacionFamiliar extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, AuditableModel, Filterable;

    protected $table = 'med_planificaciones_familiares';
    protected $fillable = [
        'tipo',
        'ficha_preocupacional_id'  
    ];
    private static $whiteListFilter = ['*'];

}
