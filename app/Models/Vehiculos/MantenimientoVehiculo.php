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
        'imagen_evidencia', // alguna imagen de evidencia del mantenimiento
        // pendiente de realizar, realizado, retrasado, postergado, no realizado.
        // retrasado se calcula desde que se crea el registro y los km transcurridos.
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

    private static array $whiteListFilter = ['*'];
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
     * Relación para obtener la última notificacion de un modelo dado.
     */
    public function latestNotificacion()
    {
        return $this->morphOne(Notificacion::class, 'notificable')->latestOfMany();
    }

}
