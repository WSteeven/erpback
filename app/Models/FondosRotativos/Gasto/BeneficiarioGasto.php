<?php

namespace App\Models\FondosRotativos\Gasto;

use App\Models\Empleado;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class BeneficiarioGasto extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;
    use UppercaseValuesTrait;
    protected $table = 'beneficiario_gastos';
    protected $primaryKey = 'id';
    protected $fillable = [
        'gasto_id',
        'beneficiario',
        'empleado_id',
    ];
    private static $whiteListFilter = [
        'gasto',
        'gasto_id',
        'empleado_id',
        'beneficiario',
    ];
    public function gasto()
    {
        return $this->hasOne(Gasto::class, 'id','id_gasto');
    }
    public function empleado()
    {
        return $this->hasOne(Empleado::class, 'id', 'empleado_id');
    }
    public function gastos(){
        return $this->hasMany(Gasto::class,'id','id_gasto');
    }
}
