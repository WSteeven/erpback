<?php

namespace App\Models\RecursosHumanos\NominaPrestamos;

use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;

class ValorEmpleadoRolMensual extends Model implements Auditable
{
    use HasFactory;
    use UppercaseValuesTrait, Filterable;
    use AuditableModel;

    protected $table = 'rrhh_nomina_valores_empleados_rol_mensual';
    protected $fillable = [
        'tipo',
        'mes',
        'empleado_id',
        'monto',
        'model_type',
        'model_id',
        'rol_pago_id',
    ];
    const INGRESO = 'INGRESO';
    const EGRESO = 'EGRESO';
}
