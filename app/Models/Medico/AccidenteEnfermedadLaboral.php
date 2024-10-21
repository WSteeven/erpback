<?php

namespace App\Models\Medico;

use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

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
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Model|\Eloquent $accidentable
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @method static \Illuminate\Database\Eloquent\Builder|AccidenteEnfermedadLaboral acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|AccidenteEnfermedadLaboral filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|AccidenteEnfermedadLaboral ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|AccidenteEnfermedadLaboral newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AccidenteEnfermedadLaboral newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AccidenteEnfermedadLaboral query()
 * @method static \Illuminate\Database\Eloquent\Builder|AccidenteEnfermedadLaboral setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|AccidenteEnfermedadLaboral setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|AccidenteEnfermedadLaboral setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|AccidenteEnfermedadLaboral whereAccidentableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccidenteEnfermedadLaboral whereAccidentableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccidenteEnfermedadLaboral whereCalificadoIss($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccidenteEnfermedadLaboral whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccidenteEnfermedadLaboral whereFecha($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccidenteEnfermedadLaboral whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccidenteEnfermedadLaboral whereInstitutoSeguridadSocial($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccidenteEnfermedadLaboral whereObservacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccidenteEnfermedadLaboral whereTipo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccidenteEnfermedadLaboral whereUpdatedAt($value)
 * @mixin \Eloquent
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
