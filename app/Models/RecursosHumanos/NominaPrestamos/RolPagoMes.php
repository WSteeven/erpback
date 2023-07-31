<?php

namespace App\Models\RecursosHumanos\NominaPrestamos;

use App\Models\Empleado;
use Carbon\Carbon;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RolPagoMes extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;
    protected $table = 'rol_pago_mes';
    protected $fillable = [
        'mes',
        'nombre',
        'finalizado'
    ];
    private static $whiteListFilter = [
        'id',
        'mes',
        'nombre',
        'finalizado'
    ];
    protected $casts = ['finalizado' => 'boolean'];
    public function rolPago()
    {
        return $this->hasMany(RolPago::class,'rol_pago_id','id');
    }

}
