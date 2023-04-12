<?php

namespace App\Models\FondosRotativos\Gasto;


use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class GastoVehiculo extends  Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;
    protected $table = 'gasto_vehiculos';
    protected $fillable = [
        'id_gasto',
        'placa',
        'kilometraje',
    ];
    private static $whiteListFilter = [
        'gasto',
        'id_gasto',
        'placa',
        'kilometraje',
    ];
    public function gasto_info()
    {
        return $this->hasOne(Gasto::class, 'id','id_gasto');
    }

}
