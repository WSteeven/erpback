<?php

namespace App\Models\Medico;

use App\Models\Cargo;
use App\Models\Empleado;
use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;

/**
 * App\Models\Medico\FichaPreocupacional
 *
 * @property int $id
 * @property string $establecimiento_salud
 * @property string|null $numero_archivo
 * @property string $lateralidad
 * @property string|null $area_trabajo
 * @property string|null $actividades_relevantes_puesto_trabajo_ocupar
 * @property string $motivo_consulta
 * @property string|null $actividades_extralaborales
 * @property string|null $enfermedad_actual
 * @property int $hijos_muertos
 * @property int $hijos_vivos
 * @property string|null $observacion_examen_fisico_regional
 * @property string|null $recomendaciones_tratamiento
 * @property string $grupo_sanguineo
 * @property int $cargo_id
 * @property int $religion_id
 * @property int $orientacion_sexual_id
 * @property int $identidad_genero_id
 * @property int $registro_empleado_examen_id
 * @property int $profesional_salud_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Medico\AccidenteEnfermedadLaboral> $accidentesEnfermedades
 * @property-read int|null $accidentes_enfermedades_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Medico\ActividadFisica> $actividadesFisicas
 * @property-read int|null $actividades_fisicas_count
 * @property-read \App\Models\Medico\AntecedentePersonal|null $antecedentePersonal
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Medico\AntecedenteClinico> $antecedentesClinicos
 * @property-read int|null $antecedentes_clinicos_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Medico\AntecedenteFamiliar> $antecedentesFamiliares
 * @property-read int|null $antecedentes_familiares_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Medico\AntecedenteTrabajoAnterior> $antecedentesTrabajosAnteriores
 * @property-read int|null $antecedentes_trabajos_anteriores_count
 * @property-read \App\Models\Medico\AptitudMedica|null $aptitudesMedicas
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read Cargo|null $cargo
 * @property-read \App\Models\Medico\ConstanteVital|null $constanteVital
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Medico\DiagnosticoFicha> $diagnosticos
 * @property-read int|null $diagnosticos_count
 * @property-read Empleado|null $empleado
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Medico\ExamenFisicoRegional> $examenesFisicosRegionales
 * @property-read int|null $examenes_fisicos_regionales_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Medico\ExamenRealizado> $examenesRealizados
 * @property-read int|null $examenes_realizados_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Medico\FrPuestoTrabajoActual> $frPuestoTrabajoActual
 * @property-read int|null $fr_puesto_trabajo_actual_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Medico\ResultadoHabitoToxico> $habitosToxicos
 * @property-read int|null $habitos_toxicos_count
 * @property-read \App\Models\Medico\IdentidadGenero|null $identidadGenero
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Medico\Medicacion> $medicaciones
 * @property-read int|null $medicaciones_count
 * @property-read \App\Models\Medico\OrientacionSexual|null $orientacionSexual
 * @property-read \App\Models\Medico\RegistroEmpleadoExamen|null $registroEmpleadoExamen
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Medico\RevisionActualOrganoSistema> $revisionesActualesOrganosSistemas
 * @property-read int|null $revisiones_actuales_organos_sistemas_count
 * @method static \Illuminate\Database\Eloquent\Builder|FichaPreocupacional acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|FichaPreocupacional filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|FichaPreocupacional ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|FichaPreocupacional newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FichaPreocupacional newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FichaPreocupacional query()
 * @method static \Illuminate\Database\Eloquent\Builder|FichaPreocupacional setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|FichaPreocupacional setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|FichaPreocupacional setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|FichaPreocupacional whereActividadesExtralaborales($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FichaPreocupacional whereActividadesRelevantesPuestoTrabajoOcupar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FichaPreocupacional whereAreaTrabajo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FichaPreocupacional whereCargoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FichaPreocupacional whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FichaPreocupacional whereEnfermedadActual($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FichaPreocupacional whereEstablecimientoSalud($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FichaPreocupacional whereGrupoSanguineo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FichaPreocupacional whereHijosMuertos($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FichaPreocupacional whereHijosVivos($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FichaPreocupacional whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FichaPreocupacional whereIdentidadGeneroId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FichaPreocupacional whereLateralidad($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FichaPreocupacional whereMotivoConsulta($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FichaPreocupacional whereNumeroArchivo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FichaPreocupacional whereObservacionExamenFisicoRegional($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FichaPreocupacional whereOrientacionSexualId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FichaPreocupacional whereProfesionalSaludId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FichaPreocupacional whereRecomendacionesTratamiento($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FichaPreocupacional whereRegistroEmpleadoExamenId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FichaPreocupacional whereReligionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FichaPreocupacional whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class FichaPreocupacional extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, AuditableModel, Filterable;

    protected $table = 'med_fichas_preocupacionales';
    protected $fillable = [
        'establecimiento_salud',
        'numero_archivo',
        'religion_id',
        'lateralidad',
        'orientacion_sexual_id',
        'identidad_genero_id',
        'area_trabajo',
        'actividades_relevantes_puesto_trabajo_ocupar',
        'motivo_consulta',
        'actividades_extralaborales',
        'enfermedad_actual',
        'recomendaciones_tratamiento',
        'grupo_sanguineo',
        'cargo_id',
        'registro_empleado_examen_id',
        'observacion_examen_fisico_regional',
        'profesional_salud_id',
    ];

    private static $whiteListFilter = ['*'];

    //Esta relación se utiliza para llenar el item C de la ficha preocupacional
    public function antecedentesClinicos()
    {
        return $this->morphMany(AntecedenteClinico::class, 'antecedentable');
    }

    //Esta relación se utiliza para llenar los items de examenes realizados del literal C de la ficha preocupacional
    public function examenesRealizados()
    {
        return $this->hasMany(ExamenRealizado::class);
    }
    public function antecedentePersonal()
    {
        return $this->hasOne(AntecedentePersonal::class, 'ficha_preocupacional_id', 'id')->with('antecedenteGinecoobstetrico');
    }
    public function habitosToxicos()
    {
        return $this->morphMany(ResultadoHabitoToxico::class, 'habitable', 'habito_toxicable_type', 'habito_toxicable_id');
    }
    public function actividadesFisicas()
    {
        return $this->morphMany(ActividadFisica::class, 'actividable');
    }
    public function medicaciones()
    {
        return $this->morphMany(Medicacion::class, 'medicable');
    }
    public function antecedentesTrabajosAnteriores()
    {
        return $this->hasMany(AntecedenteTrabajoAnterior::class, 'ficha_preocupacional_id', 'id');
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
    public function orientacionSexual()
    {
        return $this->hasOne(OrientacionSexual::class, 'id', 'orientacion_sexual_id');
    }
    public function identidadGenero()
    {
        return $this->hasOne(IdentidadGenero::class, 'id', 'identidad_genero_id');
    }
    public function empleado()
    {
        return $this->hasOne(Empleado::class, 'id', 'empleado_id');
    }

    public function cargo()
    {
        return $this->belongsTo(Cargo::class);
    }

    // public function descripcionAntecedenteTrabajo()
    // {
    //     return $this->hasOne(DescripcionAntecedenteTrabajo::class, 'ficha_preocupacional_id', 'id');
    // }
    // public function factoresRiesgo()
    // {
    //     return $this->hasMany(FactorRiesgo::class, 'ficha_preocupacional_id', 'id');
    // }
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
    public function registroEmpleadoExamen()
    {
        return $this->belongsTo(RegistroEmpleadoExamen::class);
    }
}
