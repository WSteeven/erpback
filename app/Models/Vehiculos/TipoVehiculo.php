<?php

namespace App\Models\Vehiculos;

use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

/**
 * App\Models\Vehiculos\TipoVehiculo
 *
 * @property int $id
 * @property string $nombre
 * @property bool $activo
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Vehiculos\Vehiculo> $vehiculos
 * @property-read int|null $vehiculos_count
 * @method static \Illuminate\Database\Eloquent\Builder|TipoVehiculo acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoVehiculo filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoVehiculo ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoVehiculo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TipoVehiculo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TipoVehiculo query()
 * @method static \Illuminate\Database\Eloquent\Builder|TipoVehiculo setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoVehiculo setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoVehiculo setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoVehiculo whereActivo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoVehiculo whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoVehiculo whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoVehiculo whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoVehiculo whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class TipoVehiculo extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use UppercaseValuesTrait, Filterable, Searchable;

    protected $table = 'veh_tipos_vehiculos';
    protected $fillable = [
        'nombre',
        'activo',
    ];
    
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
        'activo' => 'boolean',
    ];

    private static $whiteListFilter = [
        '*',
    ];

    /**
     * Relación uno a muchos.
     * Un tipo de vehiculo puede estar en uno o varios vehiculos
     */
    public function vehiculos()
    {
        return $this->hasMany(Vehiculo::class);
    }
}
