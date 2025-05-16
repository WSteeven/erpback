<?php

namespace App\Models\Medico;

use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;

/**
 * App\Models\Medico\TipoEvaluacionMedicaRetiro
 *
 * @property int $id
 * @property string $nombre
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @method static \Illuminate\Database\Eloquent\Builder|TipoEvaluacionMedicaRetiro acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoEvaluacionMedicaRetiro filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoEvaluacionMedicaRetiro ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoEvaluacionMedicaRetiro newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TipoEvaluacionMedicaRetiro newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TipoEvaluacionMedicaRetiro query()
 * @method static \Illuminate\Database\Eloquent\Builder|TipoEvaluacionMedicaRetiro setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoEvaluacionMedicaRetiro setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoEvaluacionMedicaRetiro setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoEvaluacionMedicaRetiro whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoEvaluacionMedicaRetiro whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoEvaluacionMedicaRetiro whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoEvaluacionMedicaRetiro whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class TipoEvaluacionMedicaRetiro extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, AuditableModel, Filterable;

    protected $table = 'med_tipos_evaluaciones_medica_retiros';
    protected $fillable = [
        'nombre',
    ];

    private static $whiteListFilter = ['*'];
}
