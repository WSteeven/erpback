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
        'esatblecimiento_salud',
        'numero_historia_clinica',
        'numero_archivo',
        'puesto_trabajo',
        'religion_id',
        'orientacion_sexual_id',
        'identidad_genero_id',
        'actividades_relevantes_puesto_trabajo_ocupar',
        'motivo_consulta',
        'empleado_id',
        'actividad_fisica',
        'enfermedad_actual',
        'recomendaciones_tratamiento',
        'descripcion_examen_fisico_regional',
        'descripcion_revision_organos_sistemas'
    ];
    private static $whiteListFilter = ['*'];

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
    public function antecedentePersonal()
    {
        return $this->hasOne(AntecedentePersonal::class, 'ficha_preocupacional_id', 'id')->with('antecedenteGinecoobstetrico');
    }

    public function examenesPreocupacionales()
    {
        return  $this->hasMany(ResultadoExamenPreocupacional::class, 'ficha_preocupacional_id', 'id');
    }
    public function habitosToxicos()
    {
        return $this->hasMany(ResultadoHabitoToxico::class, 'ficha_preocupacional_id', 'id');
    }
    public function actividadFisica()
    {
        return $this->hasMany(ActividadFisica::class, 'ficha_preocupacional_id', 'id');
    }
    public function medicaciones()
    {
        return $this->hasMany(Medicacion::class, 'ficha_preocupacional_id', 'id');
    }
    public function antecedentesTrabajosAnteriores()
    {
        return $this->hasMany(AntecedenteTrabajoAnterior::class, 'ficha_preocupacional_id', 'id');
    }
    public function descripcionAntecedenteTrabajo()
    {
        return $this->hasOne(DescripcionAntecedenteTrabajo::class, 'ficha_preocupacional_id', 'id');
    }
    public function antecedentesFamiliares()
    {
        return $this->hasMany(AntecedenteFamiliar::class, 'ficha_preocupacional_id', 'id');
    }
    public function actividadesPuestoTrabajo()
    {
        return $this->hasMany(ActividadPuestoTrabajo::class, 'ficha_preocupacional_id', 'id');
    }
    public function factoresRiesgo()
    {
        return $this->hasMany(FactorRiesgo::class, 'ficha_preocupacional_id', 'id');
    }
    public function revisionesActualesOrganosSistemas()
    {
        return $this->hasMany(RevisionActualOrganoSistema::class, 'ficha_preocupacional_id', 'id');
    }
    public function constanteVital()
    {
        return $this->hasOne(ConstanteVital::class, 'ficha_preocupacional_id', 'id');
    }
    public function examenesFisicosRegionales()
    {
        return $this->hasMany(ExamenFisicoRegional::class, 'ficha_preocupacional_id', 'id');
    }
    public function aptitudesMedicas()
    {
        return $this->hasMany(AptitudMedica::class, 'ficha_preocupacional_id', 'id');
    }
}
