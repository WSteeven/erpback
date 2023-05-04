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
    const APROBADO = 1;
    const RECHAZADO = 2;
    const PENDIENTE = 3;
    const CANCELADO = 4;
    protected $fillable = [
        'motivo_id','fecha_inicio','fecha_fin','justificacion','estado_permiso_id', 'empleado_id'
    ];

    private static $whiteListFilter = [
        'id',
        'empleado',
        'motivo',
        'estado_permiso',
        'justificacion',
        'fecha_inicio',
        'fecha_fin',
    ];
    public function empleado()
    {
        return $this->belongsTo(Empleado::class, 'empleado_id','id');
    }
}
