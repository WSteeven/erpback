<?php

namespace App\Models\RecursosHumanos\NominaPrestamos;

use App\Models\Autorizacion;
use App\Models\Empleado;
use App\Models\Notificacion;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class LicenciaEmpleado extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;
    const PENDIENTE = 1;
    const APROBADO = 2;
    const CANCELADO = 3;
    protected $table = 'licencia_empleados';
    protected $fillable = [
        'empleado',
        'id_tipo_licencia',
        'estado',
        'fecha_inicio',
        'fecha_fin',
        'justificacion',
        'documento'
    ];

    private static $whiteListFilter = [
        'id',
        'empleado',
        'tipo_licencia',
        'estado',
        'fecha_inicio',
        'fecha_fin',
        'justificacion',
        'documento'
    ];
    public function empleado_info(){
        return $this->hasOne(Empleado::class,'id', 'empleado');
    }
    public function estado_info(){
        return $this->hasOne(Autorizacion::class,'id', 'estado');
    }
    public function notificaciones()
    {
        return $this->morphMany(Notificacion::class, 'notificable');
    }
}
