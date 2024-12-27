<?php

namespace App\Models\RecursosHumanos\TrabajoSocial;

use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;

class ComposicionFamiliar extends Model implements Auditable
{
    use HasFactory;
    use UppercaseValuesTrait, Filterable;
    use AuditableModel;

    protected $table = 'rrhh_ts_composiciones_familiares';
    protected $fillable = [
        'empleado_id',
        'nombres_apellidos',
        'parentesco',
        'edad',
        'estado_civil',
        'instruccion',
        'ocupacion',
        'discapacidad',
        'ingreso_mensual',
        'model_id',
        'model_type',
    ];

    public function composicionable()
    {
        return $this->morphTo();
    }

}
