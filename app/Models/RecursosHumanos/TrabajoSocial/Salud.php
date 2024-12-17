<?php

namespace App\Models\RecursosHumanos\TrabajoSocial;

use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;

class Salud extends Model implements Auditable
{
    use HasFactory;
    use UppercaseValuesTrait, Filterable;
    use AuditableModel;

    protected $table = 'rrhh_ts_salud_empleados';
    protected $fillable = [
        'empleado_id',
        'tiene_discapacidad',
        'discapacidades',
        'discapacidades_familiar_dependiente',
        'tiene_enfermedad_cronica',
        'enfermedad_cronica',
        'alergias',
        'lugar_atencion',
        'tiene_familiar_dependiente_discapacitado',
        'nombre_familiar_dependiente_discapacitado',
        'parentesco_familiar_discapacitado',
        'model_id',
        'model_type',
    ];

    public function saludable()
    {
        return $this->morphTo();
    }
}
