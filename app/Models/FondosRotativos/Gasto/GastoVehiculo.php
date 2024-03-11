<?php

namespace App\Models\FondosRotativos\Gasto;

use App\Traits\UppercaseValuesTrait;
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
    use UppercaseValuesTrait;
    protected $table = 'gasto_vehiculos';
    protected $primaryKey = 'id_gasto';
    protected $fillable = [
        'id_gasto',
        'placa',
        'id_vehiculo',
        'kilometraje',
        'es_vehiculo_alquilado'
    ];
    private static $whiteListFilter = [
        'gasto',
        'id_gasto',
        'placa',
        'vehiculo',
        'kilometraje',
        'es_vehiculo_alquilado'
    ];
    public function gasto_info()
    {
        return $this->hasOne(Gasto::class, 'id','id_gasto');
    }

    protected $casts = [
        'es_vehiculo_alquilado' => 'boolean',

    ];

}
