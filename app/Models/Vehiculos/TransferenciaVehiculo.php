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
