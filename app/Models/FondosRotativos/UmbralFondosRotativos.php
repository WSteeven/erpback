<?php

namespace App\Models\FondosRotativos;

use App\Models\Empleado;
use App\Models\FondosRotativos\Saldo\SaldoGrupo;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class UmbralFondosRotativos extends  Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;
    use UppercaseValuesTrait;
    protected $table = 'fr_umbral_fondos_rotativos';
    protected $primaryKey = 'id';
    protected $fillable = [
        'valor_minimo',
        'referencia',
        'empleado_id',
    ];
    private static $whiteListFilter = [
        'referencia',
        'empleado_id',
        'valor_minimo',
    ];
    public function empleado()
    {
        return $this->hasOne(Empleado::class, 'id', 'empleado_id');
    }

}
