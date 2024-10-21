<?php

namespace App\Models\Medico;

use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

/**
 * App\Models\Medico\RiesgoAntecedenteEmpleoAnterior
 *
 * @property int $id
 * @property int|null $tipo_riesgo_id
 * @property int $antecedente_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Medico\AntecedenteTrabajoAnterior|null $antecedente
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Medico\TipoFactorRiesgo|null $tipoRiesgo
 * @method static \Illuminate\Database\Eloquent\Builder|RiesgoAntecedenteEmpleoAnterior acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|RiesgoAntecedenteEmpleoAnterior filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|RiesgoAntecedenteEmpleoAnterior ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|RiesgoAntecedenteEmpleoAnterior newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RiesgoAntecedenteEmpleoAnterior newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RiesgoAntecedenteEmpleoAnterior query()
 * @method static \Illuminate\Database\Eloquent\Builder|RiesgoAntecedenteEmpleoAnterior setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|RiesgoAntecedenteEmpleoAnterior setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|RiesgoAntecedenteEmpleoAnterior setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|RiesgoAntecedenteEmpleoAnterior whereAntecedenteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RiesgoAntecedenteEmpleoAnterior whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RiesgoAntecedenteEmpleoAnterior whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RiesgoAntecedenteEmpleoAnterior whereTipoRiesgoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RiesgoAntecedenteEmpleoAnterior whereUpdatedAt($value)
 * @mixin \Eloquent
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
