<?php

namespace App\Models\Medico;

use App\Models\Empleado;
use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;

class FichaPreocupacional extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, AuditableModel, Filterable;

    protected $table = 'med_fichas_preocupacionales';
    protected $fillable = [
        'ciu',
        'establecimiento_salud',
        'numero_historia_clinica',
        'numero_archivo',
        'puesto_trabajo',
        'religion_id',
        'lateralidad',
        'orientacion_sexual_id',
        'identidad_genero_id',
        'actividades_relevantes_puesto_trabajo_ocupar',
        'motivo_consulta',
        'vida_sexual_activa',
        'actividades_extralaborales',
        'enfermedad_actual',
        'registro_empleado_examen_id',
        'actividad_fisica',
        'recomendaciones_tratamiento',
        'descripcion_examen_fisico_regional',
        'descripcion_revision_organos_sistemas'
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
        return $this->hasOne(ExamenRealizado::class);
    }
    public function antecedentePersonal()
    {
        return $this->hasOne(AntecedentePersonal::class, 'ficha_preocupacional_id', 'id')->with('antecedenteGinecoobstetrico');
    }
    public function habitosToxicos()
    {
        return $this->morphMany(ResultadoHabitoToxico::class, 'habitable');
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
        return $this->morphMany(FrPuestoTrabajoActual::class, 'factorRiesgoTrabajable');
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

    public function examenesPreocupacionales()
    {
        return  $this->hasMany(ResultadoExamenPreocupacional::class, 'ficha_preocupacional_id', 'id');
    }
    public function descripcionAntecedenteTrabajo()
    {
        return $this->hasOne(DescripcionAntecedenteTrabajo::class, 'ficha_preocupacional_id', 'id');
    }
    public function factoresRiesgo()
    {
        return $this->hasMany(FactorRiesgo::class, 'ficha_preocupacional_id', 'id');
    }
    public function revisionesActualesOrganosSistemas()
    {
        return $this->morphMany(RevisionActualOrganoSistema::class, 'revisionable');
    }
    public function constanteVital()
    {
        return $this->morphOne(ConstanteVital::class, 'constanteVitalable');
    }
    public function examenesFisicosRegionales()
    {
        return $this->morphMany(ExamenFisicoRegional::class, 'examenFisicoRegionalable');
    }
    public function aptitudesMedicas()
    {
        return $this->hasMany(AptitudMedica::class, 'ficha_preocupacional_id', 'id');
    }
}
