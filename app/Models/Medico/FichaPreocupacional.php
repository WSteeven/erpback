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
