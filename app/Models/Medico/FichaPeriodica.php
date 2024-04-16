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
        'esatblecimiento_salud',
        'numero_historia_clinica',
        'numero_archivo',
        'puesto_trabajo',
        'motivo_consulta',
        'registro_empleado_examen_id',
        'actividad_fisica',
        'enfermedad_actual',
        'recomendaciones_tratamiento',
        'descripcion_examen_fisico_regional',
        'descripcion_revision_organos_sistemas'
    ];
}
