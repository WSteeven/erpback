<?php

namespace App\Models\RecursosHumanos\NominaPrestamos;

use App\Models\Empleado;
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
        'periodo_id',
        'valor_utilidad',
        'plazo',
        'estado',
        'id_solicitud_prestamo_empresarial'
    ];
    public function plazo_prestamo_empresarial_info()
    {
        return $this->hasMany(PlazoPrestamoEmpresarial::class, 'id_prestamo_empresarial', 'id');
    }
    public function empleado_info()
    {
        return $this->hasOne(Empleado::class, 'id', 'solicitante');
    }
    public function solicitud_prestamo_empresarial_info()
    {
        return $this->hasOne(SolicitudPrestamoEmpresarial::class, 'id', 'id_solicitud_prestamo_empresarial');
    }
    public function periodo_info(){
        return $this->hasOne(Periodo::class, 'id', 'periodo_id');
    }
    private static $whiteListFilter = [
        'id',
        'solicitante',
        'fecha',
        'monto',
        'periodo',
        'valor_utilidad',
        'forma_pago',
        'solicitud_prestamo_empresarial',
        'plazo',
        'estado'
    ];
}
