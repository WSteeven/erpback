<?php

namespace App\Models\Vehiculos;

use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

/**
 * App\Models\Vehiculos\ChecklistVehiculo
 *
 * @method static where(string $string, int $bitacora_id)
 * @method static create(array $datos)
 * @property int $id
 * @property int $bitacora_id
 * @property string $parabrisas
 * @property string $limpiaparabrisas
 * @property string $luces_interiores
 * @property string $aire_acondicionado
 * @property string $aceite_motor
 * @property string $liquido_freno
 * @property string $aceite_hidraulico
 * @property string $liquido_refrigerante
 * @property string $filtro_combustible
 * @property string $bateria
 * @property string $agua_plumas_radiador
 * @property string $cables_conexiones
 * @property string $luces_exteriores
 * @property string $frenos
 * @property string $amortiguadores
 * @property string $llantas
 * @property string $observacion_checklist_interior
 * @property string $observacion_checklist_bajo_capo
 * @property string $observacion_checklist_exterior
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Vehiculos\BitacoraVehicular|null $bitacora
 * @method static \Illuminate\Database\Eloquent\Builder|ChecklistVehiculo acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ChecklistVehiculo filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ChecklistVehiculo ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ChecklistVehiculo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ChecklistVehiculo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ChecklistVehiculo query()
 * @method static \Illuminate\Database\Eloquent\Builder|ChecklistVehiculo setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ChecklistVehiculo setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ChecklistVehiculo setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|ChecklistVehiculo whereAceiteHidraulico($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChecklistVehiculo whereAceiteMotor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChecklistVehiculo whereAguaPlumasRadiador($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChecklistVehiculo whereAireAcondicionado($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChecklistVehiculo whereAmortiguadores($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChecklistVehiculo whereBateria($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChecklistVehiculo whereBitacoraId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChecklistVehiculo whereCablesConexiones($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChecklistVehiculo whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChecklistVehiculo whereFiltroCombustible($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChecklistVehiculo whereFrenos($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChecklistVehiculo whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChecklistVehiculo whereLimpiaparabrisas($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChecklistVehiculo whereLiquidoFreno($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChecklistVehiculo whereLiquidoRefrigerante($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChecklistVehiculo whereLlantas($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChecklistVehiculo whereLucesExteriores($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChecklistVehiculo whereLucesInteriores($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChecklistVehiculo whereObservacionChecklistBajoCapo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChecklistVehiculo whereObservacionChecklistExterior($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChecklistVehiculo whereObservacionChecklistInterior($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChecklistVehiculo whereParabrisas($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChecklistVehiculo whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ChecklistVehiculo extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel, UppercaseValuesTrait, Filterable;
    public $table = 'veh_checklist_vehiculos';
    public $fillable = [
        'bitacora_id',
        'parabrisas',
        'limpiaparabrisas',
        'luces_interiores',
        'aire_acondicionado',
        'aceite_motor',
        'liquido_freno',
        'aceite_hidraulico',
        'liquido_refrigerante',
        'filtro_combustible',
        'bateria',
        'agua_plumas_radiador',
        'cables_conexiones',
        'luces_exteriores',
        'frenos',
        'amortiguadores',
        'llantas',
        'observacion_checklist_interior',
        'observacion_checklist_bajo_capo',
        'observacion_checklist_exterior',
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
