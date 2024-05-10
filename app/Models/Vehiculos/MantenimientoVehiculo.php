<?php

namespace App\Models\Vehiculos;

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
        'imagen_evidencia', //alguna imagen de evidencia del mantenimiento 
        'estado', // pendiente de realizar, realizado, retrasado, postergado. retrasado se calcula desde que se crea el registro y los km transcurridos
        'km_retraso',
        'dias_postergado', //si estado es postergado debe poner los días aquí para que le notifique 
        'motivo_postergacion', //si estado es postergado este campo es obligatorio
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
        'firmada' => 'boolean',
    ];
}
