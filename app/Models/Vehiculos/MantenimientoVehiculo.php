<?php

namespace App\Models\Vehiculos;

use App\Models\Empleado;
use App\Models\Notificacion;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;


/**
 * App\Models\Vehiculos\MantenimientoVehiculo
 *
 * @method static where(string $string, $vehiculo_id)
 * @method static create(array $array)
 * @property int $id
 * @property int $vehiculo_id
 * @property int $servicio_id
 * @property int $empleado_id
 * @property int $supervisor_id
 * @property string|null $fecha_realizado
 * @property string|null $km_realizado
 * @property string|null $imagen_evidencia
 * @property string $estado
 * @property string|null $km_retraso
 * @property int $dias_postergado
 * @property string|null $motivo_postergacion
 * @property string|null $observacion
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read Empleado|null $empleado
 * @property-read Notificacion|null $latestNotificacion
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Notificacion> $notificaciones
 * @property-read int|null $notificaciones_count
 * @property-read \App\Models\Vehiculos\Servicio|null $servicio
 * @property-read Empleado|null $supervisor
 * @property-read \App\Models\Vehiculos\Vehiculo|null $vehiculo
 * @method static \Illuminate\Database\Eloquent\Builder|MantenimientoVehiculo acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|MantenimientoVehiculo filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|MantenimientoVehiculo ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|MantenimientoVehiculo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MantenimientoVehiculo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MantenimientoVehiculo query()
 * @method static \Illuminate\Database\Eloquent\Builder|MantenimientoVehiculo setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|MantenimientoVehiculo setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|MantenimientoVehiculo setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|MantenimientoVehiculo whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MantenimientoVehiculo whereDiasPostergado($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MantenimientoVehiculo whereEmpleadoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MantenimientoVehiculo whereEstado($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MantenimientoVehiculo whereFechaRealizado($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MantenimientoVehiculo whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MantenimientoVehiculo whereImagenEvidencia($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MantenimientoVehiculo whereKmRealizado($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MantenimientoVehiculo whereKmRetraso($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MantenimientoVehiculo whereMotivoPostergacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MantenimientoVehiculo whereObservacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MantenimientoVehiculo whereServicioId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MantenimientoVehiculo whereSupervisorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MantenimientoVehiculo whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MantenimientoVehiculo whereVehiculoId($value)
 * @mixin \Eloquent
 */
class MantenimientoVehiculo extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel, UppercaseValuesTrait, Filterable;
    protected $table = 'veh_mantenimientos_vehiculos';
    protected $fillable = [
        'vehiculo_id',
        'servicio_id', // el mantenimiento preventivo/programado
        'empleado_id', // la persona que realiza el mantenimiento en cuestión
        'supervisor_id', // la persona que actualiza los datos en el sistema
        'fecha_realizado',
        'km_realizado',
        'imagen_evidencia', //alguna imagen de evidencia del mantenimiento
        // pendiente de realizar, realizado, retrasado, postergado, no realizado.
        //retrasado se calcula desde que se crea el registro y los km transcurridos.
        // no realizado cuando el supervisor decide no realizar el mantenimiento pese a estar programado
        'estado',
        'km_retraso',
        'dias_postergado', //si estado es postergado debe poner los días aquí para que le notifique
        'motivo_postergacion', //si estado es postergado este campo es obligatorio
        'observacion'
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
        'firmada' => 'boolean',
    ];

    //Definicion de constantes de estados
    const PENDIENTE = 'PENDIENTE';
    const REALIZADO = 'REALIZADO';
    const RETRASADO = 'RETRASADO';
    const POSTERGADO = 'POSTERGADO';
    const NO_REALIZADO = 'NO REALIZADO';

    private static $whiteListFilter = ['*'];
    /**
     * ______________________________________________________________________________________
     * RELACIONES CON OTRAS TABLAS
     * ______________________________________________________________________________________
     */
    public function vehiculo()
    {
        return $this->belongsTo(Vehiculo::class);
    }
    public function servicio()
    {
        return $this->belongsTo(Servicio::class);
    }
    public function empleado()
    {
        return $this->belongsTo(Empleado::class, 'empleado_id', 'id');
    }

    public function supervisor()
    {
        return $this->belongsTo(Empleado::class, 'supervisor_id', 'id');
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
     * Relación para obtener la ultima notificacion de un modelo dado.
     */
    public function latestNotificacion()
    {
        return $this->morphOne(Notificacion::class, 'notificable')->latestOfMany();
    }

}
