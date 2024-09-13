<?php

namespace App\Models\Medico;

use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;


/**
 * App\Models\Medico\ExamenRealizado
 *
 * @property int $id
 * @property int|null $examen_id
 * @property string $tiempo
 * @property string $resultado
 * @property int|null $ficha_preocupacional_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Medico\ExamenOrganoReproductivo|null $examen
 * @property-read \App\Models\Medico\FichaPreocupacional|null $fichaPreocupacional
 * @method static \Illuminate\Database\Eloquent\Builder|ExamenRealizado acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ExamenRealizado filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ExamenRealizado ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ExamenRealizado newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ExamenRealizado newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ExamenRealizado query()
 * @method static \Illuminate\Database\Eloquent\Builder|ExamenRealizado setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ExamenRealizado setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ExamenRealizado setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|ExamenRealizado whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExamenRealizado whereExamenId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExamenRealizado whereFichaPreocupacionalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExamenRealizado whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExamenRealizado whereResultado($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExamenRealizado whereTiempo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExamenRealizado whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ExamenRealizado extends Model  implements Auditable
{
    use HasFactory, UppercaseValuesTrait, AuditableModel, Filterable;

    protected $table = 'med_examenes_realizados';
    protected $fillable = [
        'examen_id',
        'tiempo', //texto formato año-mes debe permitir solo el año o año y fecha  
        'resultado',
        'ficha_preocupacional_id',
    ];
    private static $whiteListFilter = ['*'];


    public function fichaPreocupacional()
    {
        return $this->belongsTo(fichaPreocupacional::class);
    }
    public function examen()
    {
        return $this->hasOne(ExamenOrganoReproductivo::class);
    }
}
