<?php

namespace App\Models\RecursosHumanos\NominaPrestamos;

use App\Models\Empleado;
use App\Models\FormaPago;
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
    public function plazo_prestamo_empresarial_info()
    {
        return $this->hasMany(PlazoPrestamoEmpresarial::class, 'id_prestamo_empresarial', 'id');
    }
    public function empleado_info()
    {
        return $this->hasOne(Empleado::class, 'id', 'solicitante');
    }
    public function forma_pago_info()
    {
        return $this->hasOne(FormaPago::class, 'id', 'id_forma_pago');
    }

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
