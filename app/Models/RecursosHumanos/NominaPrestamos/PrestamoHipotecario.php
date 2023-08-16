<?php

namespace App\Models\RecursosHumanos\NominaPrestamos;

use App\Models\Empleado;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class PrestamoHipotecario extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;
    protected $table = 'prestamo_hipotecario';
    protected $fillable = [
        'mes','empleado_id','nut','valor'
    ];
    protected $casts = [
        'valor' => 'decimal:2'
    ];
    private static $whiteListFilter = [
        'id',
        'empleado',
        'mes',
        'nut',
        'valor',
    ];
    public function empleado_info(){
        return $this->hasOne(Empleado::class,'id', 'empleado_id');
    }
}
