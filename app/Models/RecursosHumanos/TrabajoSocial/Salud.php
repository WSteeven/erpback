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
        // las variables comentadas son calculadas, y no se almacenan en la BD
        'empleado_id',
        'discapacidades',
        'enfermedad_cronica',
        'fecha_enfermedad_cronica',
        'alergias',
        'lugar_atencion',
        'nombre_familiar_dependiente_discapacitado',
        'imagen_cedula_familiar_dependiente_discapacitado',
        'parentesco_familiar_discapacitado',
        'discapacidades_familiar_dependiente',
        'frecuencia_asiste_medico',
        'deporte_practicado', // pueden ser varios
        'frecuencia_practica_deporte',
        'model_id',
        'model_type',
    ];

    protected $casts = [
        'discapacidades' => 'array',
        'discapacidades_familiar_dependiente' => 'array',
    ];

    public function saludable()
    {
        return $this->morphTo();
    }
}
