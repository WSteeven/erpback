<?php

namespace App\Models\RecursosHumanos\NominaPrestamos;

use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class PrestamoEmpresarial extends Model  implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;
    protected $table = 'prestamo_empresarial';

    protected $fillable = [
        'solicitante',
        'fecha',
        'monto',
        'utilidad',
        'valor_utilidad',
        'id_forma_pago',
        'plazo',
        'estado'
    ];

    private static $whiteListFilter = [
        'id',
        'solicitante',
        'fecha',
        'monto',
        'utilidad',
        'valor_utilidad',
        'id_forma_pago',
        'plazo',
        'estado'
    ];
}
