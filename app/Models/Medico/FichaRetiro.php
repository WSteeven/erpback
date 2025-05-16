<?php

namespace App\Models\Medico;

use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

/**
 * App\Models\Medico\FichaRetiro
 *
 * @property int $id
 * @property string $ciu
 * @property string $establecimiento_salud
 * @property string $numero_historia_clinica
 * @property string $numero_archivo
 * @property string $fecha_inicio_labores
 * @property string $fecha_salida
 * @property int $evaluacion_retiro
 * @property string|null $observacion_retiro
 * @property string|null $recomendacion_tratamiento
 * @property bool $se_realizo_evaluacion_medica_retiro
 * @property string|null $observacion_evaluacion_medica_retiro
 * @property string|null $antecedentes_clinicos_quirurgicos
 * @property int|null $cargo_id
 * @property int $registro_empleado_examen_id
 * @property int $profesional_salud_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Medico\AccidenteEnfermedadLaboral> $accidentesEnfermedades
 * @property-read int|null $accidentes_enfermedades_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Medico\AntecedenteClinico> $antecedentesClinicos
 * @property-read int|null $antecedentes_clinicos_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Medico\ConstanteVital|null $constanteVital
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Medico\DiagnosticoFicha> $diagnosticos
 * @property-read int|null $diagnosticos_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Medico\ExamenFisicoRegional> $examenesFisicosRegionales
 * @property-read int|null $examenes_fisicos_regionales_count
 * @property-read \App\Models\Medico\RegistroEmpleadoExamen|null $registroEmpleadoExamen
 * @method static \Illuminate\Database\Eloquent\Builder|FichaRetiro acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|FichaRetiro filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|FichaRetiro ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|FichaRetiro newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FichaRetiro newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FichaRetiro query()
 * @method static \Illuminate\Database\Eloquent\Builder|FichaRetiro setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|FichaRetiro setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|FichaRetiro setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|FichaRetiro whereAntecedentesClinicosQuirurgicos($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FichaRetiro whereCargoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FichaRetiro whereCiu($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FichaRetiro whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FichaRetiro whereEstablecimientoSalud($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FichaRetiro whereEvaluacionRetiro($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FichaRetiro whereFechaInicioLabores($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FichaRetiro whereFechaSalida($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FichaRetiro whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FichaRetiro whereNumeroArchivo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FichaRetiro whereNumeroHistoriaClinica($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FichaRetiro whereObservacionEvaluacionMedicaRetiro($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FichaRetiro whereObservacionRetiro($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FichaRetiro whereProfesionalSaludId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FichaRetiro whereRecomendacionTratamiento($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FichaRetiro whereRegistroEmpleadoExamenId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FichaRetiro whereSeRealizoEvaluacionMedicaRetiro($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FichaRetiro whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class FichaRetiro extends Model implements Auditable
{
    use HasFactory;
    use UppercaseValuesTrait, AuditableModel, Filterable;

    protected $table = "med_fichas_retiros";
    protected $fillable = [
        'ciu',
        'establecimiento_salud',
        'numero_historia_clinica',
        'numero_archivo',
        'fecha_inicio_labores',
        'fecha_salida',
        'evaluacion_retiro', //boolean
        'observacion_retiro',
        'recomendacion_tratamiento',
        'se_realizo_evaluacion_medica_retiro',
        'observacion_evaluacion_medica_retiro',
        'antecedentes_clinicos_quirurgicos',
        'cargo_id',
        'registro_empleado_examen_id',
        'profesional_salud_id',
    ];
    private static $whiteListFilter = ['*'];

    protected $casts = [
        'se_realizo_evaluacion_medica_retiro' => 'boolean',
    ];

    public function antecedentesClinicos()
    {
        return $this->morphMany(AntecedenteClinico::class, 'antecedentable');
    }

    public function accidentesEnfermedades() //accidentes de trabajo y enfermedades laborales
    {
        return $this->morphMany(AccidenteEnfermedadLaboral::class, 'accidentable');
    }

    public function constanteVital()
    {
        return $this->morphOne(ConstanteVital::class, 'constanteVitalable', 'constante_vitalable_type', 'constante_vitalable_id');
    }

    public function examenesFisicosRegionales()
    {
        return $this->morphMany(ExamenFisicoRegional::class, 'examenFisicoRegionalable', 'examen_fisico_regionalable_type', 'examen_fisico_regionalable_id');
    }

    public function diagnosticos()
    {
        return $this->morphMany(DiagnosticoFicha::class, 'diagnosticable');
    }

    public function registroEmpleadoExamen()
    {
        return $this->belongsTo(RegistroEmpleadoExamen::class);
    }
}
