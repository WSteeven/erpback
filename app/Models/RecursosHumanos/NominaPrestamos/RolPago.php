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
        'salario',
        'dias',
        'sueldo',
        'decimo_tercero',
        'decimo_cuarto',
        'fondos_reserva',
        'alimentacion',
        'horas_extras',
        'total_ingreso',
        'comisiones',
        'iess',
        'anticipo',
        'prestamo_quirorafario',
        'prestamo_hipotecario',
        'extension_conyugal',
        'prestamo_empresarial',
        'sancion_pecuniaria',
        'total_egreso'

    ];
    private static $whiteListFilter = [
        'id',
        'empleado',
        'salario',
        'dias',
        'sueldo',
        'decimo_tercero',
        'decimo_cuarto',
        'fondos_reserva',
        'alimentacion',
        'horas_extras',
        'total_ingreso',
        'comisiones',
        'iess',
        'anticipo',
        'prestamo_quirorafario',
        'prestamo_hipotecario',
        'extension_conyugal',
        'prestamo_empresarial',
        'sancion_pecuniaria',
        'total_egreso'
    ];

    public function empleado_info()
    {
        return $this->belongsTo(Empleado::class, 'empleado_id', 'id');
    }
}
