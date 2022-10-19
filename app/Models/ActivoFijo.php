<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivoFijo extends Model
{
    use HasFactory;
    protected $table = 'activos_fijos';
    protected $fillable = [
        'fecha_desde', 
        'fecha_hasta', 
        'accion', 
        'observacion', 
        'lugar', 
        'detalle_id', 
        'empleado_id',
        'sucursal_id',
        'condicion_id',
    ];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
    ];

    const ASIGNACION = 'ASIGNACION'; //cuando se entrega el activo al empleado
    const DEVOLUCION = 'DEVOLUCION'; //cuando devuelve el activo a bodega



}
