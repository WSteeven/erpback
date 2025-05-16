<?php

namespace App\Models\Medico;

use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

/**
 * App\Models\Medico\FichaPeriodica
 *
 * @property int $id
 * @property string $establecimiento_salud
 * @property string $numero_historia_clinica
 * @property string $numero_archivo
 * @property string|null $puesto_trabajo
 * @property string $motivo_consulta
 * @property string|null $incidentes
 * @property string|null $antecedentes_clinicos_quirurgicos
 * @property int $registro_empleado_examen_id
 * @property string|null $enfermedad_actual
 * @property string|null $observacion_examen_fisico_regional
 * @property int|null $cargo_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $profesional_salud_id
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Medico\AccidenteEnfermedadLaboral> $accidentesEnfermedades
 * @property-read int|null $accidentes_enfermedades_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Medico\ActividadFisica> $actividadesFisicas
 * @property-read int|null $actividades_fisicas_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Medico\AntecedenteClinico> $antecedentesClinicos
 * @property-read int|null $antecedentes_clinicos_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Medico\AntecedenteFamiliar> $antecedentesFamiliares
 * @property-read int|null $antecedentes_familiares_count
 * @property-read \App\Models\Medico\AptitudMedica|null $aptitudesMedicas
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Medico\ConstanteVital|null $constanteVital
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Medico\DiagnosticoFicha> $diagnosticos
 * @property-read int|null $diagnosticos_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Medico\ExamenFisicoRegional> $examenesFisicosRegionales
 * @property-read int|null $examenes_fisicos_regionales_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Medico\FrPuestoTrabajoActual> $frPuestoTrabajoActual
 * @property-read int|null $fr_puesto_trabajo_actual_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Medico\ResultadoHabitoToxico> $habitosToxicos
 * @property-read int|null $habitos_toxicos_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Medico\Medicacion> $medicaciones
 * @property-read int|null $medicaciones_count
 * @property-read \App\Models\Medico\RegistroEmpleadoExamen|null $registroEmpleadoExamen
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Medico\RevisionActualOrganoSistema> $revisionesActualesOrganosSistemas
 * @property-read int|null $revisiones_actuales_organos_sistemas_count
 * @method static \Illuminate\Database\Eloquent\Builder|FichaPeriodica acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|FichaPeriodica filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|FichaPeriodica ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|FichaPeriodica newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FichaPeriodica newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FichaPeriodica query()
 * @method static \Illuminate\Database\Eloquent\Builder|FichaPeriodica setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|FichaPeriodica setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|FichaPeriodica setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|FichaPeriodica whereAntecedentesClinicosQuirurgicos($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FichaPeriodica whereCargoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FichaPeriodica whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FichaPeriodica whereEnfermedadActual($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FichaPeriodica whereEstablecimientoSalud($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FichaPeriodica whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FichaPeriodica whereIncidentes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FichaPeriodica whereMotivoConsulta($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FichaPeriodica whereNumeroArchivo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FichaPeriodica whereNumeroHistoriaClinica($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FichaPeriodica whereObservacionExamenFisicoRegional($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FichaPeriodica whereProfesionalSaludId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FichaPeriodica wherePuestoTrabajo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FichaPeriodica whereRegistroEmpleadoExamenId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FichaPeriodica whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class FichaPeriodica extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, AuditableModel, Filterable;

    protected $table = 'med_fichas_periodicas';
    protected $fillable = [
        'incidentes',
        'establecimiento_salud',
        'numero_historia_clinica',
        'numero_archivo',
        'puesto_trabajo',
        'motivo_consulta',
        'incidentes',
        'antecedentes_clinicos_quirurgicos',
        'registro_empleado_examen_id',
        'enfermedad_actual',
        'observacion_examen_fisico_regional',
        'profesional_salud_id',
        'cargo_id',
    ];

    private static $whiteListFilter = ['*'];

    public function antecedentesClinicos()
    {
        return $this->morphMany(AntecedenteClinico::class, 'antecedentable');
    }

    public function actividadesFisicas()
    {
        return $this->morphMany(ActividadFisica::class, 'actividable');
    }

    public function habitosToxicos()
    {
        return $this->morphMany(ResultadoHabitoToxico::class, 'habitable', 'habito_toxicable_type', 'habito_toxicable_id');
    }
    public function accidentesEnfermedades() //accidentes de trabajo y enfermedades laborales
    {
        return $this->morphMany(AccidenteEnfermedadLaboral::class, 'accidentable');
    }
    public function antecedentesFamiliares()
    {
        return $this->morphMany(AntecedenteFamiliar::class, 'antecedentable');
    }
    public function frPuestoTrabajoActual()
    {
        return $this->morphMany(FrPuestoTrabajoActual::class, 'factorRiesgoTrabajable', 'factor_riesgo_puesto_trabajable_type', 'factor_riesgo_puesto_trabajable_id');
    }
    public function revisionesActualesOrganosSistemas()
    {
        return $this->morphMany(RevisionActualOrganoSistema::class, 'revisionable');
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
    public function aptitudesMedicas()
    {
        return $this->morphOne(AptitudMedica::class, 'aptitudable');
    }

    public function medicaciones()
    {
        return $this->morphMany(Medicacion::class, 'medicable');
    }

    public function registroEmpleadoExamen()
    {
        return $this->belongsTo(RegistroEmpleadoExamen::class);
    }
}
