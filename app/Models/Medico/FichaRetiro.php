<?php

namespace App\Models\Medico;

use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

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
