<?php

namespace App\Models\RecursosHumanos\NominaPrestamos;

use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class PlazoPrestamoEmpresarial extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;
    protected $table = 'plazo_prestamo_empresarial';
    protected $fillable = [
        'num_cuota',
        'fecha_pago',
        'valor_a_pagar',
        'id_prestamo_empresarial',
        'pago_couta'
    ];

    private static $whiteListFilter = [
        'id',
        'num_cuota',
        'fecha_pago',
        'valor_a_pagar',
        'prestamo_empresarial',
        'pago_couta'
    ];
    public function prestamo_info()
    {
        return $this->hasOne(PrestamoEmpresarial::class, 'id','id_prestamo_empresarial');
    }

}
