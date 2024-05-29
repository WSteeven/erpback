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

class Vacacion extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;
    protected $table = 'vacaciones';
    const PENDIENTE = 1;
    const APROBADO = 2;
    const CANCELADO = 3;
    protected $fillable = [
        'empleado_id',
        'periodo_id',
        'numero_rangos',
        'fecha_inicio',
        'fecha_fin',
        'fecha_inicio_rango1_vacaciones',
        'fecha_fin_rango1_vacaciones',
        'fecha_inicio_rango2_vacaciones',
        'fecha_fin_rango2_vacaciones',
        'estado',
    ];
    public function estado_info(){
        return $this->hasOne(Autorizacion::class,'id','estado');
    }
    public function empleado_info()
    {
        return $this->belongsTo(Empleado::class, 'empleado_id', 'id')->with('departamento','jefe');
    }
    public function periodo_info()
    {
        return $this->hasOne(Periodo::class, 'id', 'periodo_id');
    }
    public function estado_permiso_info()
    {
        return $this->belongsTo(Autorizacion::class, 'estado', 'id');
    }
    public function notificaciones()
    {
        return $this->morphMany(Notificacion::class, 'notificable');
    }
    private static $whiteListFilter = [
        'id',
        'empleado',
        'periodo',
        'fecha_inicio',
        'fecha_fin',
        'fecha_inicio_rango1_vacaciones',
        'fecha_fin_rango1_vacaciones',
        'fecha_inicio_rango2_vacaciones',
        'fecha_fin_rango2_vacaciones',
        'estado'
    ];
}
