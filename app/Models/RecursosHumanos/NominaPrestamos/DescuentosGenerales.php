<?php

namespace App\Models\RecursosHumanos\NominaPrestamos;

use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class DescuentosGenerales extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;
    protected $table = 'descuentos_generales';
    protected $fillable = [
        'nombre',
        'abreviatura'
    ];

    private static $whiteListFilter = [
        'id',
        'nombre',
        'abreviatura'
    ];
    public function egreso_rol_pago()
    {
        return $this->morphMany(EgresoRolPago::class, 'descuento');
    }

}
