<?php

namespace App\Models;

use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class PermisoEmpleado extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;
    protected $table = 'permiso_empleados';
    protected $fillable = [
        'motivo_id','fecha_inicio','fecha_fin','justificacion','estado_permiso_id'
    ];

    private static $whiteListFilter = [
        'id',
        'nombre',
        'motivo',
        'estado_permiso',
        'justificacion',
        'fecha_inicio',
        'fecha_fin',
    ];
}
