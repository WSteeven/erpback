<?php

namespace App\Models\FondosRotativos\Gasto;

use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

/**
 * App\Models\FondosRotativos\Gasto\GastoVehiculo
 *
 * @property int $id_gasto
 * @property string $placa
 * @property int $kilometraje
 * @property int|null $id_vehiculo
 * @property bool $es_vehiculo_alquilado
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\FondosRotativos\Gasto\Gasto|null $gasto
 * @method static \Illuminate\Database\Eloquent\Builder|GastoVehiculo acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|GastoVehiculo filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|GastoVehiculo ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|GastoVehiculo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GastoVehiculo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GastoVehiculo query()
 * @method static \Illuminate\Database\Eloquent\Builder|GastoVehiculo setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|GastoVehiculo setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|GastoVehiculo setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|GastoVehiculo whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GastoVehiculo whereEsVehiculoAlquilado($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GastoVehiculo whereIdGasto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GastoVehiculo whereIdVehiculo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GastoVehiculo whereKilometraje($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GastoVehiculo wherePlaca($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GastoVehiculo whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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
    public function gasto()
    {
        return $this->hasOne(Gasto::class, 'id','id_gasto');
    }

    protected $casts = [
        'es_vehiculo_alquilado' => 'boolean',

    ];

}
