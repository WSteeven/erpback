<?php

namespace App\Models\RecursosHumanos\NominaPrestamos;

use App\Models\Autorizacion;
use App\Models\Empleado;
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
    protected $table = 'vacacion';
    protected $fillable = [
        'empleado_id',
        'periodo_id',
        'descuento_vacaciones',
        'fecha_inicio_rango1_vacaciones',
        'fecha_fin_rango1_vacaciones',
        'fecha_inicio_rango2_vacaciones',
        'fecha_fin_rango2_vacaciones',
        'solicitud',
    ];
    public function estado_info(){
        return $this->hasOne(Autorizacion::class,'id','estado');
    }
    public function empleado_info()
    {
        return $this->hasOne(Empleado::class, 'id', 'solicitante');
    }

    private static $whiteListFilter = [
        'id',
        'empleado',
        'periodo',
        'descuento_vacaciones',
        'fecha_inicio_rango1_vacaciones',
        'fecha_fin_rango1_vacaciones',
        'fecha_inicio_rango2_vacaciones',
        'fecha_fin_rango2_vacaciones',
        'solicitud',
    ];
}
