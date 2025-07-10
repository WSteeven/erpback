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
 * App\Models\Medico\AccidenteEnfermedadLaboral
 *
 * @property int $id
 * @property string $tipo
 * @property string|null $observacion
 * @property bool $calificado_iss
 * @property string|null $instituto_seguridad_social
 * @property string|null $fecha
 * @property int $accidentable_id
 * @property string $accidentable_type
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Model|Eloquent $accidentable
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @method static Builder|AccidenteEnfermedadLaboral acceptRequest(?array $request = null)
 * @method static Builder|AccidenteEnfermedadLaboral filter(?array $request = null)
 * @method static Builder|AccidenteEnfermedadLaboral ignoreRequest(?array $request = null)
 * @method static Builder|AccidenteEnfermedadLaboral newModelQuery()
 * @method static Builder|AccidenteEnfermedadLaboral newQuery()
 * @method static Builder|AccidenteEnfermedadLaboral query()
 * @method static Builder|AccidenteEnfermedadLaboral setBlackListDetection(?array $black_list_detections = null)
 * @method static Builder|AccidenteEnfermedadLaboral setCustomDetection(?array $object_custom_detect = null)
 * @method static Builder|AccidenteEnfermedadLaboral setLoadInjectedDetection($load_default_detection)
 * @method static Builder|AccidenteEnfermedadLaboral whereAccidentableId($value)
 * @method static Builder|AccidenteEnfermedadLaboral whereAccidentableType($value)
 * @method static Builder|AccidenteEnfermedadLaboral whereCalificadoIss($value)
 * @method static Builder|AccidenteEnfermedadLaboral whereCreatedAt($value)
 * @method static Builder|AccidenteEnfermedadLaboral whereFecha($value)
 * @method static Builder|AccidenteEnfermedadLaboral whereId($value)
 * @method static Builder|AccidenteEnfermedadLaboral whereInstitutoSeguridadSocial($value)
 * @method static Builder|AccidenteEnfermedadLaboral whereObservacion($value)
 * @method static Builder|AccidenteEnfermedadLaboral whereTipo($value)
 * @method static Builder|AccidenteEnfermedadLaboral whereUpdatedAt($value)
 * @mixin Eloquent
 */
class AccidenteEnfermedadLaboral extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, AuditableModel, Filterable;

    protected $table = 'med_accidentes_enfermedades_laborales';
    protected $fillable = [
        'tipo',
        'observacion',
        'calificado_iss',
        'instituto_seguridad_social',
        'fecha',
        'accidentable_id',
        'accidentable_type',
    ];

    const ACCIDENTE_TRABAJO = 'ACCIDENTE DE TRABAJO';
    const ENFERMEDAD_PROFESIONAL = 'ENFERMEDAD PROFESIONAL';

    protected $casts = [
        'calificado_iss' => 'boolean',
    ];

    // Relación polimórfica
    public function accidentable()
    {
        return $this->morphTo();
    }
}
