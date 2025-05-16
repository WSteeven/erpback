<?php

namespace App\Models\Vehiculos;

use App\Models\Archivo;
use App\Models\Canton;
use App\Models\Empleado;
use App\Models\Notificacion;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;


/**
 * App\Models\Vehiculos\AsignacionVehiculo
 *
 * @method static ignoreRequest(string[] $array)
 * @method static where(\Closure $param)
 * @method static create(mixed $datos)
 * @property int $id
 * @property int|null $vehiculo_id
 * @property int|null $entrega_id
 * @property int|null $responsable_id
 * @property int|null $canton_id
 * @property string|null $observacion_entrega
 * @property string|null $observacion_recibe
 * @property string $fecha_entrega
 * @property string $estado
 * @property int $transferido
 * @property int $devuelto
 * @property string|null $fecha_devolucion
 * @property int|null $devuelve_id
 * @property string|null $observaciones_devolucion
 * @property string|null $accesorios
 * @property string|null $estado_carroceria
 * @property string|null $estado_mecanico
 * @property string|null $estado_electrico
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $garaje
 * @property string|null $latitud
 * @property string|null $longitud
 * @property int|null $garaje_id
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Archivo> $archivos
 * @property-read int|null $archivos_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read Canton|null $canton
 * @property-read Empleado|null $entrega
 * @property-read Notificacion|null $latestNotificacion
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Notificacion> $notificaciones
 * @property-read int|null $notificaciones_count
 * @property-read Empleado|null $responsable
 * @property-read \App\Models\Vehiculos\Vehiculo|null $vehiculo
 * @method static \Illuminate\Database\Eloquent\Builder|AsignacionVehiculo acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|AsignacionVehiculo filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|AsignacionVehiculo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AsignacionVehiculo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AsignacionVehiculo query()
 * @method static \Illuminate\Database\Eloquent\Builder|AsignacionVehiculo setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|AsignacionVehiculo setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|AsignacionVehiculo setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|AsignacionVehiculo whereAccesorios($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AsignacionVehiculo whereCantonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AsignacionVehiculo whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AsignacionVehiculo whereDevuelto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AsignacionVehiculo whereDevuelveId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AsignacionVehiculo whereEntregaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AsignacionVehiculo whereEstado($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AsignacionVehiculo whereEstadoCarroceria($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AsignacionVehiculo whereEstadoElectrico($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AsignacionVehiculo whereEstadoMecanico($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AsignacionVehiculo whereFechaDevolucion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AsignacionVehiculo whereFechaEntrega($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AsignacionVehiculo whereGaraje($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AsignacionVehiculo whereGarajeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AsignacionVehiculo whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AsignacionVehiculo whereLatitud($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AsignacionVehiculo whereLongitud($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AsignacionVehiculo whereObservacionEntrega($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AsignacionVehiculo whereObservacionRecibe($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AsignacionVehiculo whereObservacionesDevolucion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AsignacionVehiculo whereResponsableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AsignacionVehiculo whereTransferido($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AsignacionVehiculo whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AsignacionVehiculo whereVehiculoId($value)
 * @mixin \Eloquent
 */
class AsignacionVehiculo extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use UppercaseValuesTrait, Filterable, Searchable;

    protected $table = 'veh_asignaciones_vehiculos';
    protected $fillable = [
        'vehiculo_id',
        'entrega_id',
        'canton_id',
        'responsable_id',
        'observacion_recibe',
        'observacion_entrega',
        'fecha_entrega',
        'estado',
        'transferido',
        'devuelto',
        'observaciones_devolucion',
        'devuelve_id',
        'fecha_devolucion',
        'accesorios',
        'estado_carroceria',
        'estado_mecanico',
        'estado_electrico',
        'garaje_id',
        'latitud',
        'longitud',
    ];

    //estados
    const PENDIENTE = 'PENDIENTE';
    const ACEPTADO = 'ACEPTADO';
    const RECHAZADO = 'RECHAZADO';
    const ANULADO = 'ANULADO';

    private static $whiteListFilter = ['*'];

    /**
     * ______________________________________________________________________________________
     * RELACIONES CON OTRAS TABLAS
     * ______________________________________________________________________________________
     */
    /**
     * Relación uno a muchos (inversa).
     */
    public function vehiculo()
    {
        return $this->belongsTo(Vehiculo::class);
    }

    /**
     * Relación uno a muchos (inversa).
     */
    public function canton()
    {
        return $this->belongsTo(Canton::class);
    }


    /**
     * Realación uno a muchos (inversa).
     * Un vehiculo tiene solo un tipo de vehiculo a la vez.
     */
    public function entrega()
    {
        return $this->belongsTo(Empleado::class, 'entrega_id', 'id');
    }

    /**
     * Relación uno a muchos
     */
    public function responsable()
    {
        return $this->belongsTo(Empleado::class, 'responsable_id', 'id');
    }

    /**
     * Relación para obtener la ultima notificacion de un modelo dado.
     */
    public function latestNotificacion()
    {
        return $this->morphOne(Notificacion::class, 'notificable')->latestOfMany();
    }

    /**
     * Relacion polimorfica a una notificacion.
     * Una orden de compra puede tener una o varias notificaciones.
     */
    public function notificaciones()
    {
        return $this->morphMany(Notificacion::class, 'notificable');
    }

    /**
     * Relacion polimorfica con Archivos uno a muchos.
     *
     */
    public function archivos()
    {
        return $this->morphMany(Archivo::class, 'archivable');
    }
}
