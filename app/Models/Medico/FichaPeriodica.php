<?php

namespace App\Models\Medico;

use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class FichaPeriodica extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, AuditableModel, Filterable;

    protected $table = 'med_fichas_periodicas';
    protected $fillable = [
        'ciu',
        'establecimiento_salud',
        'numero_historia_clinica',
        'numero_archivo',
        'puesto_trabajo',
        'motivo_consulta',
        'incidentes',
        'registro_empleado_examen_id',
        'enfermedad_actual',
        'observacion_examen_fisico_regional',
        'profesional_salud_id',
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
}
