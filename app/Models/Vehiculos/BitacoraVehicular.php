<?php

namespace App\Models\Vehiculos;

use App\Models\ActividadRealizada;
use App\Models\Empleado;
use App\Models\Notificacion;
use App\Traits\UppercaseValuesTrait;
use Eloquent;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Carbon;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Models\Audit;

/**
 * App\Models\Vehiculos\BitacoraVehicular
 *
 * @method static ignoreRequest(string[] $array)
 * @method static filter()
 * @method static where(string $string, $id)
 * @property int $id
 * @property string $fecha
 * @property string $imagen_inicial
 * @property string $hora_salida
 * @property string|null $hora_llegada
 * @property float $km_inicial
 * @property float|null $km_final
 * @property int $tanque_inicio
 * @property int|null $tanque_final
 * @property bool $firmada
 * @property string|null $fecha_finalizacion
 * @property int|null $chofer_id
 * @property int|null $vehiculo_id
 * @property int|null $registrador_id
 * @property string|null $tareas
 * @property string|null $tickets
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, ActividadRealizada> $actividades
 * @property-read int|null $actividades_count
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @property-read ChecklistAccesoriosVehiculo|null $checklistAccesoriosVehiculo
 * @property-read ChecklistImagenVehiculo|null $checklistImagenVehiculo
 * @property-read ChecklistVehiculo|null $checklistVehiculo
 * @property-read Empleado|null $chofer
 * @property-read Notificacion|null $latestNotificacion
 * @property-read Collection<int, Notificacion> $notificaciones
 * @property-read int|null $notificaciones_count
 * @property-read Empleado|null $registrador
 * @property-read Vehiculo|null $vehiculo
 * @method static Builder|BitacoraVehicular acceptRequest(?array $request = null)
 * @method static Builder|BitacoraVehicular newModelQuery()
 * @method static Builder|BitacoraVehicular newQuery()
 * @method static Builder|BitacoraVehicular query()
 * @method static Builder|BitacoraVehicular setBlackListDetection(?array $black_list_detections = null)
 * @method static Builder|BitacoraVehicular setCustomDetection(?array $object_custom_detect = null)
 * @method static Builder|BitacoraVehicular setLoadInjectedDetection($load_default_detection)
 * @method static Builder|BitacoraVehicular whereChoferId($value)
 * @method static Builder|BitacoraVehicular whereCreatedAt($value)
 * @method static Builder|BitacoraVehicular whereFecha($value)
 * @method static Builder|BitacoraVehicular whereFechaFinalizacion($value)
 * @method static Builder|BitacoraVehicular whereFirmada($value)
 * @method static Builder|BitacoraVehicular whereHoraLlegada($value)
 * @method static Builder|BitacoraVehicular whereHoraSalida($value)
 * @method static Builder|BitacoraVehicular whereId($value)
 * @method static Builder|BitacoraVehicular whereImagenInicial($value)
 * @method static Builder|BitacoraVehicular whereKmFinal($value)
 * @method static Builder|BitacoraVehicular whereKmInicial($value)
 * @method static Builder|BitacoraVehicular whereRegistradorId($value)
 * @method static Builder|BitacoraVehicular whereTanqueFinal($value)
 * @method static Builder|BitacoraVehicular whereTanqueInicio($value)
 * @method static Builder|BitacoraVehicular whereTareas($value)
 * @method static Builder|BitacoraVehicular whereTickets($value)
 * @method static Builder|BitacoraVehicular whereUpdatedAt($value)
 * @method static Builder|BitacoraVehicular whereVehiculoId($value)
 * @mixin Eloquent
 */
class BitacoraVehicular extends Pivot implements Auditable
{
    use HasFactory;
    use AuditableModel, UppercaseValuesTrait, Filterable;
    protected $table = 'veh_bitacoras_vehiculos';
    protected $fillable = [
        'fecha',
        'imagen_inicial',
        'hora_salida',
        'hora_llegada',
        'km_inicial',
        'km_final',
        'tanque_inicio',
        'tanque_final',
        'firmada',
        'chofer_id',
        'vehiculo_id',
        'registrador_id',
        'tareas',
        'tickets',
    ];
    public $incrementing = true;
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
        'firmada' => 'boolean',
    ];

    const LLENO = 'LLENO';
    const VACIO = 'VACIO';
    const CADUCADO = 'CADUCADO';
    const BUENO = 'BUENO';
    const MALO = 'MALO';
    const CORRECTO = 'CORRECTO';
    const ADVERTENCIA = 'ADVERTENCIA';
    const PELIGRO = 'PELIGRO';



    private static array $whiteListFilter = ['*'];
    /**
     * ______________________________________________________________________________________
     * RELACIONES CON OTRAS TABLAS
     * ______________________________________________________________________________________
     */
    public function chofer()
    {
        return $this->belongsTo(Empleado::class, 'chofer_id', 'id');
    }
    public function registrador()
    {
        return $this->belongsTo(Empleado::class, 'registrador_id', 'id');
    }
    public function vehiculo()
    {
        return $this->belongsTo(Vehiculo::class);
    }

    public function tanqueos()
    {
        return $this->hasMany(Tanqueo::class, 'bitacora_id', 'id');
    }

    public function actividades()
    {
        return $this->morphMany(ActividadRealizada::class, 'actividable');
    }

    public function checklistAccesoriosVehiculo()
    {
        return $this->hasOne(ChecklistAccesoriosVehiculo::class, 'bitacora_id');
    }

    public function checklistVehiculo()
    {
        return $this->hasOne(ChecklistVehiculo::class, 'bitacora_id');
    }

    public function checklistImagenVehiculo()
    {
        return $this->hasOne(ChecklistImagenVehiculo::class, 'bitacora_id');
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
     * Relación para obtener la última notificacion de un modelo dado.
     */
    public function latestNotificacion()
    {
        return $this->morphOne(Notificacion::class, 'notificable')->latestOfMany();
    }


    /**
     * ______________________________________________________________________________________
     * FUNCIONES
     * ______________________________________________________________________________________
     */

//    public static function crearBitacora($data)
//    {
//        return new BitacoraVehicular([
//            'fecha' => $data['fecha'],
//            'hora_salida' => $data['hora_salida'],
//            'hora_llegada' => $data['hora_llegada'],
//            'km_inicial' => $data['km_inicial'],
//            'km_final' => $data['km_final'],
//            'tanque_inicio' => $data['tanque_inicio'],
//            'tanque_final' => $data['tanque_final'],
//            'firmada' => $data['firmada'],
//            // 'chofer_id' => $data['chofer_id'],
//            'vehiculo_id' => $data['vehiculo_id'],
//        ]);
//    }
}
