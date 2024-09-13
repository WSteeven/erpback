<?php

namespace App\Models\Vehiculos;

use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

/**
 * App\Models\Vehiculos\ChecklistAccesoriosVehiculo
 *
 * @method static where(string $string, int $bitacora_id)
 * @method static create(array $datos)
 * @property int $id
 * @property int $bitacora_id
 * @property string $botiquin
 * @property string $extintor
 * @property string $caja_herramientas
 * @property string $triangulos
 * @property string $llanta_emergencia
 * @property string $cinturones
 * @property string $gata
 * @property string $portaescalera
 * @property string $observacion_accesorios_vehiculo
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Vehiculos\BitacoraVehicular|null $bitacora
 * @method static \Illuminate\Database\Eloquent\Builder|ChecklistAccesoriosVehiculo acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ChecklistAccesoriosVehiculo filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ChecklistAccesoriosVehiculo ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ChecklistAccesoriosVehiculo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ChecklistAccesoriosVehiculo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ChecklistAccesoriosVehiculo query()
 * @method static \Illuminate\Database\Eloquent\Builder|ChecklistAccesoriosVehiculo setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ChecklistAccesoriosVehiculo setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ChecklistAccesoriosVehiculo setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|ChecklistAccesoriosVehiculo whereBitacoraId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChecklistAccesoriosVehiculo whereBotiquin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChecklistAccesoriosVehiculo whereCajaHerramientas($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChecklistAccesoriosVehiculo whereCinturones($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChecklistAccesoriosVehiculo whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChecklistAccesoriosVehiculo whereExtintor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChecklistAccesoriosVehiculo whereGata($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChecklistAccesoriosVehiculo whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChecklistAccesoriosVehiculo whereLlantaEmergencia($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChecklistAccesoriosVehiculo whereObservacionAccesoriosVehiculo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChecklistAccesoriosVehiculo wherePortaescalera($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChecklistAccesoriosVehiculo whereTriangulos($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChecklistAccesoriosVehiculo whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ChecklistAccesoriosVehiculo extends Model implements Auditable
{
    use HasFactory;
    use Filterable;
    use UppercaseValuesTrait;
    use AuditableModel;
    public $table = 'veh_checklist_accesorios_vehiculos';
    public $fillable = [
        'bitacora_id',
        'botiquin',
        'extintor',
        'caja_herramientas',
        'triangulos',
        'llanta_emergencia',
        'cinturones',
        'gata',
        'portaescalera',
        'observacion_accesorios_vehiculo',
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
