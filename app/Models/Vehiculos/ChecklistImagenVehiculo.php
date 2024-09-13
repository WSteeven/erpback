<?php

namespace App\Models\Vehiculos;

use App\Models\Vehiculos\BitacoraVehicular;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

/**
 * App\Models\Vehiculos\ChecklistImagenVehiculo
 *
 * @method static where(string $string, int $bitacora_id)
 * @method static create(array $datos)
 * @property int $id
 * @property int $bitacora_id
 * @property string|null $imagen_frontal
 * @property string|null $imagen_trasera
 * @property string|null $imagen_lateral_derecha
 * @property string|null $imagen_lateral_izquierda
 * @property string|null $imagen_tablero_km
 * @property string|null $imagen_tablero_radio
 * @property string|null $imagen_asientos
 * @property string|null $imagen_accesorios
 * @property string $observacion
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read BitacoraVehicular|null $bitacora
 * @method static \Illuminate\Database\Eloquent\Builder|ChecklistImagenVehiculo acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ChecklistImagenVehiculo filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ChecklistImagenVehiculo ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ChecklistImagenVehiculo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ChecklistImagenVehiculo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ChecklistImagenVehiculo query()
 * @method static \Illuminate\Database\Eloquent\Builder|ChecklistImagenVehiculo setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ChecklistImagenVehiculo setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ChecklistImagenVehiculo setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|ChecklistImagenVehiculo whereBitacoraId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChecklistImagenVehiculo whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChecklistImagenVehiculo whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChecklistImagenVehiculo whereImagenAccesorios($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChecklistImagenVehiculo whereImagenAsientos($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChecklistImagenVehiculo whereImagenFrontal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChecklistImagenVehiculo whereImagenLateralDerecha($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChecklistImagenVehiculo whereImagenLateralIzquierda($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChecklistImagenVehiculo whereImagenTableroKm($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChecklistImagenVehiculo whereImagenTableroRadio($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChecklistImagenVehiculo whereImagenTrasera($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChecklistImagenVehiculo whereObservacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChecklistImagenVehiculo whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ChecklistImagenVehiculo extends Model implements Auditable
{
    use HasFactory;
    use Filterable;
    use AuditableModel;

    public $table = 'veh_checklist_imagenes_vehiculos';
    public $fillable = [
        'bitacora_id',
        'imagen_frontal',
        'imagen_trasera',
        'imagen_lateral_derecha',
        'imagen_lateral_izquierda',
        'imagen_tablero_km',
        'imagen_tablero_radio',
        'imagen_asientos',
        'imagen_accesorios',
        'observacion',
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
    ];

    private static $whiteListFilter = ['*'];


    /**
     * ______________________________________________________________________________________
     * RELACIONES CON OTRAS TABLAS
     * ______________________________________________________________________________________
     */
    public function bitacora()
    {
        return $this->belongsTo(BitacoraVehicular::class);
    }
}
