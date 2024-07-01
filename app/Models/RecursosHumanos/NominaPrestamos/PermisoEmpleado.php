<?php

namespace App\Models\RecursosHumanos\NominaPrestamos;

use App\Models\Archivo;
use App\Models\Autorizacion;
use App\Models\Empleado;
use App\Models\Notificacion;
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
    const PENDIENTE = 1;
    const APROBADO = 2;
    const CANCELADO = 3;
    protected $fillable = [
        'tipo_permiso_id',
        'fecha_hora_inicio',
        'fecha_hora_fin',
        'fecha_recuperacion',
        'hora_recuperacion',
        'fecha_hora_reagendamiento',
        'justificacion',
        'observacion',
        'estado_permiso_id',
        'empleado_id',
        'cargo_vacaciones',
        'aceptar_sugerencia'
    ];

    private static $whiteListFilter = [
        'id',
        'empleado',
        'tipo_permiso',
        'estado_permiso',
        'estado_permiso_id',
        'justificacion',
        'fecha_hora_inicio',
        'fecha_hora_fin',
        'fecha_recuperacion',
        'hora_recuperacion',
        'fecha_hora_reagendamiento',
        'justificacion',
        'observacion',
        'documento',
        'cargo_vacaciones',
        'aceptar_sugerencia'

    ];
    protected $casts = [
        'cargo_vacaciones' => 'boolean',
        'aceptar_sugerencia' => 'boolean',
    ];
    public function tipo_permiso_info()
    {
        return $this->belongsTo(MotivoPermisoEmpleado::class, 'tipo_permiso_id', 'id');
    }
    public function estado_permiso_info()
    {
        return $this->belongsTo(Autorizacion::class, 'estado_permiso_id', 'id');
    }
    public function empleado_info()
    {
        return $this->belongsTo(Empleado::class, 'empleado_id', 'id')->with('departamento','jefe');
    }
    public function notificaciones()
    {
        return $this->morphMany(Notificacion::class, 'notificable');
    }
    
    /**
     * Relacion polimorfica con Archivos uno a muchos.
     *
     */
    public function archivos()
    {
        return $this->morphMany(Archivo::class, 'archivable');
    }
}
