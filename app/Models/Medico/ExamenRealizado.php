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
 * App\Models\Medico\ExamenRealizado
 *
 * @property int $id
 * @property int|null $examen_id
 * @property string $tiempo
 * @property string $resultado
 * @property int|null $ficha_preocupacional_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @property-read ExamenOrganoReproductivo|null $examen
 * @property-read FichaPreocupacional|null $fichaPreocupacional
 * @method static Builder|ExamenRealizado acceptRequest(?array $request = null)
 * @method static Builder|ExamenRealizado filter(?array $request = null)
 * @method static Builder|ExamenRealizado ignoreRequest(?array $request = null)
 * @method static Builder|ExamenRealizado newModelQuery()
 * @method static Builder|ExamenRealizado newQuery()
 * @method static Builder|ExamenRealizado query()
 * @method static Builder|ExamenRealizado setBlackListDetection(?array $black_list_detections = null)
 * @method static Builder|ExamenRealizado setCustomDetection(?array $object_custom_detect = null)
 * @method static Builder|ExamenRealizado setLoadInjectedDetection($load_default_detection)
 * @method static Builder|ExamenRealizado whereCreatedAt($value)
 * @method static Builder|ExamenRealizado whereExamenId($value)
 * @method static Builder|ExamenRealizado whereFichaPreocupacionalId($value)
 * @method static Builder|ExamenRealizado whereId($value)
 * @method static Builder|ExamenRealizado whereResultado($value)
 * @method static Builder|ExamenRealizado whereTiempo($value)
 * @method static Builder|ExamenRealizado whereUpdatedAt($value)
 * @mixin Eloquent
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
    private static array $whiteListFilter = ['*'];


    public function fichaPreocupacional()
    {
        return $this->belongsTo(FichaPreocupacional::class);
    }
    public function examen()
    {
        return $this->hasOne(ExamenOrganoReproductivo::class);
    }
}
