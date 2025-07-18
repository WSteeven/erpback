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
 * App\Models\Medico\RiesgoAntecedenteEmpleoAnterior
 *
 * @property int $id
 * @property int|null $tipo_riesgo_id
 * @property int $antecedente_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read AntecedenteTrabajoAnterior|null $antecedente
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @property-read TipoFactorRiesgo|null $tipoRiesgo
 * @method static Builder|RiesgoAntecedenteEmpleoAnterior acceptRequest(?array $request = null)
 * @method static Builder|RiesgoAntecedenteEmpleoAnterior filter(?array $request = null)
 * @method static Builder|RiesgoAntecedenteEmpleoAnterior ignoreRequest(?array $request = null)
 * @method static Builder|RiesgoAntecedenteEmpleoAnterior newModelQuery()
 * @method static Builder|RiesgoAntecedenteEmpleoAnterior newQuery()
 * @method static Builder|RiesgoAntecedenteEmpleoAnterior query()
 * @method static Builder|RiesgoAntecedenteEmpleoAnterior setBlackListDetection(?array $black_list_detections = null)
 * @method static Builder|RiesgoAntecedenteEmpleoAnterior setCustomDetection(?array $object_custom_detect = null)
 * @method static Builder|RiesgoAntecedenteEmpleoAnterior setLoadInjectedDetection($load_default_detection)
 * @method static Builder|RiesgoAntecedenteEmpleoAnterior whereAntecedenteId($value)
 * @method static Builder|RiesgoAntecedenteEmpleoAnterior whereCreatedAt($value)
 * @method static Builder|RiesgoAntecedenteEmpleoAnterior whereId($value)
 * @method static Builder|RiesgoAntecedenteEmpleoAnterior whereTipoRiesgoId($value)
 * @method static Builder|RiesgoAntecedenteEmpleoAnterior whereUpdatedAt($value)
 * @mixin Eloquent
 */
class RiesgoAntecedenteEmpleoAnterior extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, Filterable, AuditableModel;

    protected $table = 'med_riesgos_antecedentes_trabajos_anteriores';
    protected $fillable = [
        'tipo_riesgo_id',
        'antecedente_id',
    ];


    public function tipoRiesgo(){
        return $this->belongsTo(TipoFactorRiesgo::class);
    }
    public function antecedente(){
        return $this->belongsTo(AntecedenteTrabajoAnterior::class);
    }
}
