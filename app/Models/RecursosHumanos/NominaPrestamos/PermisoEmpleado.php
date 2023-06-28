<?php

namespace App\Models\RecursosHumanos\NominaPrestamos;

use App\Models\Empleado;
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
        'tipo_permiso_id',
        'fecha_hora_inicio',
        'fecha_hora_fin',
        'fecha_recuperacion',
        'hora_recuperacion',
        'justificacion',
        'estado_permiso_id',
        'empleado_id'
    ];

    private static $whiteListFilter = [
        'id',
        'empleado',
        'tipo_permiso',
        'estado_permiso',
        'justificacion',
        'fecha_hora_inicio',
        'fecha_hora_fin',
        'fecha_recuperacion',
        'hora_recuperacion',
        'justificacion',
        'documento',

    ];
    public function tipo_permiso_info()
    {
        return $this->belongsTo(MotivoPermisoEmpleado::class, 'tipo_permiso_id', 'id');
    }
    public function estado_permiso_info()
    {
        return $this->belongsTo(EstadoPermisoEmpleado::class, 'estado_permiso_id', 'id');
    }
    public function empleado_info()
    {
        return $this->belongsTo(Empleado::class, 'empleado_id', 'id');
    }
}
