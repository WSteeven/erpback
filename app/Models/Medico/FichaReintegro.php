<?php

namespace App\Models\Medico;

use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;

class FichaReintegro extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, AuditableModel, Filterable;

    protected $table = 'med_fichas_reintegros';
    protected $fillable = [
        'fecha_ultimo_dia_laboral',
        'fecha_reingreso',
        'causa_salida',
        //
        'motivo_consulta',
        'enfermedad_actual',
        'observacion_examen_fisico_regional',
        'cargo_id',
        'profesional_salud_id',
        'registro_empleado_examen_id',
    ];

    private static $whiteListFilter = ['*'];

    public function registroEmpleadoExamen()
    {
        return $this->belongsTo(RegistroEmpleadoExamen::class);
    }

    public function constanteVital()
    {
        return $this->morphOne(ConstanteVital::class, 'constanteVitalable', 'constante_vitalable_type', 'constante_vitalable_id');
    }

    public function examenesFisicosRegionales()
    {
        return $this->morphMany(ExamenFisicoRegional::class, 'examenFisicoRegionalable', 'examen_fisico_regionalable_type', 'examen_fisico_regionalable_id');
    }

    public function aptitudesMedicas()
    {
        return $this->morphOne(AptitudMedica::class, 'aptitudable');
    }
}
