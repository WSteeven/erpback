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
 * @method static ignoreRequest(string[] $array)
 * @method static filter()
 * @method static where(string $string, $id)
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
