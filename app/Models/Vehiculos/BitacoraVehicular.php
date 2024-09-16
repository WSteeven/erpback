<?php

namespace App\Models\Vehiculos;

use App\Models\ActividadRealizada;
use App\Models\Empleado;
use App\Models\Notificacion;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;

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
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, ActividadRealizada> $actividades
 * @property-read int|null $actividades_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Vehiculos\ChecklistAccesoriosVehiculo|null $checklistAccesoriosVehiculo
 * @property-read \App\Models\Vehiculos\ChecklistImagenVehiculo|null $checklistImagenVehiculo
 * @property-read \App\Models\Vehiculos\ChecklistVehiculo|null $checklistVehiculo
 * @property-read Empleado|null $chofer
 * @property-read Notificacion|null $latestNotificacion
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Notificacion> $notificaciones
 * @property-read int|null $notificaciones_count
 * @property-read Empleado|null $registrador
 * @property-read \App\Models\Vehiculos\Vehiculo|null $vehiculo
 * @method static \Illuminate\Database\Eloquent\Builder|BitacoraVehicular acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|BitacoraVehicular newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BitacoraVehicular newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BitacoraVehicular query()
 * @method static \Illuminate\Database\Eloquent\Builder|BitacoraVehicular setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|BitacoraVehicular setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|BitacoraVehicular setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|BitacoraVehicular whereChoferId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BitacoraVehicular whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BitacoraVehicular whereFecha($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BitacoraVehicular whereFechaFinalizacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BitacoraVehicular whereFirmada($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BitacoraVehicular whereHoraLlegada($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BitacoraVehicular whereHoraSalida($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BitacoraVehicular whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BitacoraVehicular whereImagenInicial($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BitacoraVehicular whereKmFinal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BitacoraVehicular whereKmInicial($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BitacoraVehicular whereRegistradorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BitacoraVehicular whereTanqueFinal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BitacoraVehicular whereTanqueInicio($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BitacoraVehicular whereTareas($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BitacoraVehicular whereTickets($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BitacoraVehicular whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BitacoraVehicular whereVehiculoId($value)
 * @mixin \Eloquent
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

    public static function crearBitacora($data)
    {
        return new BitacoraVehicular([
            'fecha' => $data['fecha'],
            'hora_salida' => $data['hora_salida'],
            'hora_llegada' => $data['hora_llegada'],
            'km_inicial' => $data['km_inicial'],
            'km_final' => $data['km_final'],
            'tanque_inicio' => $data['tanque_inicio'],
            'tanque_final' => $data['tanque_final'],
            'firmada' => $data['firmada'],
            // 'chofer_id' => $data['chofer_id'],
            'vehiculo_id' => $data['vehiculo_id'],
        ]);
    }
}
