<?php

namespace App\Models\RecursosHumanos\NominaPrestamos;

use App\Models\Empleado;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class RolPago extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;
    protected $table = 'rol_pago';
    protected $fillable = [
        'empleado_id',
        'mes',
        'dias',
        'sueldo',
        'decimo_tercero',
        'decimo_cuarto',
        'total_ingreso',
        'iess',
        'total_egreso',
        'total'

    ];
    private static $whiteListFilter = [
        'id',
        'mes',
        'empleado',
        'dias',
        'sueldo',
        'total_ingreso',
        'total_egreso',
        'total'
    ];

    public function empleado_info()
    {
        return $this->belongsTo(Empleado::class, 'empleado_id', 'id');
    }

    public function egreso_rol_pago()
    {
        return $this->hasMany(EgresoRolPago::class,'id_rol_pago', 'id')->with('descuento');
    }
    public function ingreso_rol_pago()
    {
        return $this->hasMany(IngresoRolPago::class,'id_rol_pago', 'id')->with('concepto_ingreso_info');
    }

}
