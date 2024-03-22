<?php

namespace App\Models\Medico;

use App\ModelFilters\Medico\ConsultaMedicaFilter;
use App\Models\Empleado;
use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;

class ConsultaMedica extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, AuditableModel, Filterable, ConsultaMedicaFilter;

    protected $table = 'med_consultas_medicas';

    protected $fillable = [
        'observacion',
        'cita_medica_id',
        'registro_empleado_examen_id',
    ];

    private static $whiteListFilter = ['*'];

    public function registroEmpleadoExamen()
    {
        return $this->belongsTo(RegistroEmpleadoExamen::class);
    }

    public function citaMedica()
    {
        return $this->belongsTo(CitaMedica::class);
    }

    public function receta()
    {
        return $this->hasOne(Receta::class);
    }

    public function diagnosticosCitaMedica()
    {
        return $this->hasMany(DiagnosticoCitaMedica::class);
    }
}
