<?php

namespace App\Models\Medico;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use App\Traits\UppercaseValuesTrait;

/**
 * App\Models\Medico\Receta
 *
 * @property int $id
 * @property string|null $rp
 * @property string|null $prescripcion
 * @property int $consulta_medica_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Medico\ConsultaMedica|null $consultaMedica
 * @method static \Illuminate\Database\Eloquent\Builder|Receta acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Receta filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Receta ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Receta newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Receta newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Receta query()
 * @method static \Illuminate\Database\Eloquent\Builder|Receta setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Receta setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Receta setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|Receta whereConsultaMedicaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Receta whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Receta whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Receta wherePrescripcion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Receta whereRp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Receta whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Receta extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, Filterable, AuditableModel;

    protected $table = 'med_recetas';
    protected $fillable = [
        'rp',
        'prescripcion',
        'consulta_medica_id',
        // 'cita_medica_id',
        // 'registro_empleado_examen_id',
    ];

    private static $whiteListFilter = ['*'];

    public function consultaMedica()
    {
        return $this->belongsTo(ConsultaMedica::class);
    }
}
