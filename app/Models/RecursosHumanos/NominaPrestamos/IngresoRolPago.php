<?php

namespace App\Models\RecursosHumanos\NominaPrestamos;

use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class IngresoRolPago extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;
    protected $table = 'ingreso_rol_pago';
    protected $fillable = [
        'concepto',
        'id_rol_pago',
        'monto'
    ];

    private static $whiteListFilter = [
        'id',
        'rol_pago',
        'monto'
    ];
    public function concepto_ingreso_info()
    {
        return $this->hasOne(ConceptoIngreso::class,'id', 'concepto');
    }

}
