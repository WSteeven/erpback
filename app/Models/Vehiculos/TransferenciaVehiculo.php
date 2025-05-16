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
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

/**
 * App\Models\Vehiculos\TransferenciaVehiculo
 *
 * @property int $id
 * @property int|null $vehiculo_id
 * @property int|null $entrega_id
 * @property int|null $responsable_id
 * @property int|null $canton_id
 * @property string $motivo
 * @property string|null $observacion_entrega
 * @property string|null $observacion_recibe
 * @property string $fecha_entrega
 * @property string $estado
 * @property int $transferido
 * @property int $devuelto
 * @property string|null $fecha_devolucion
 * @property int|null $devuelve_id
 * @property int|null $asignacion_id
 * @property int|null $transferencia_id
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
 * @method static \Illuminate\Database\Eloquent\Builder|TransferenciaVehiculo acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|TransferenciaVehiculo filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|TransferenciaVehiculo ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|TransferenciaVehiculo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TransferenciaVehiculo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TransferenciaVehiculo query()
 * @method static \Illuminate\Database\Eloquent\Builder|TransferenciaVehiculo setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|TransferenciaVehiculo setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|TransferenciaVehiculo setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|TransferenciaVehiculo whereAccesorios($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransferenciaVehiculo whereAsignacionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransferenciaVehiculo whereCantonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransferenciaVehiculo whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransferenciaVehiculo whereDevuelto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransferenciaVehiculo whereDevuelveId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransferenciaVehiculo whereEntregaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransferenciaVehiculo whereEstado($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransferenciaVehiculo whereEstadoCarroceria($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransferenciaVehiculo whereEstadoElectrico($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransferenciaVehiculo whereEstadoMecanico($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransferenciaVehiculo whereFechaDevolucion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransferenciaVehiculo whereFechaEntrega($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransferenciaVehiculo whereGaraje($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransferenciaVehiculo whereGarajeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransferenciaVehiculo whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransferenciaVehiculo whereLatitud($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransferenciaVehiculo whereLongitud($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransferenciaVehiculo whereMotivo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransferenciaVehiculo whereObservacionEntrega($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransferenciaVehiculo whereObservacionRecibe($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransferenciaVehiculo whereObservacionesDevolucion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransferenciaVehiculo whereResponsableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransferenciaVehiculo whereTransferenciaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransferenciaVehiculo whereTransferido($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransferenciaVehiculo whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransferenciaVehiculo whereVehiculoId($value)
 * @mixin \Eloquent
 */
class TransferenciaVehiculo extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel, UppercaseValuesTrait, Filterable;

    protected $table = 'veh_transferencias_vehiculos';
    protected $fillable = [
        'vehiculo_id',
        'entrega_id',
        'responsable_id',
        'canton_id',
        'motivo',
        'observacion_recibe',
        'observacion_entrega',
        'fecha_entrega',
        'estado',
        'transferido',
        'devuelto',
        'observaciones_devolucion',
        'devuelve_id',
        'asignacion_id',
        'transferencia_id',
        'fecha_devolucion',
        'accesorios',
        'estado_carroceria',
        'estado_mecanico',
        'estado_electrico',
        'garaje_id',
        'latitud',
        'longitud',
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
