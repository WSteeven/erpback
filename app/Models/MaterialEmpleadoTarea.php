<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialEmpleadoTarea extends Model
{
    use HasFactory;

    protected $table = 'materiales_empleados_tareas';

    protected $fillable = [
        'cantidad_stock',
        'es_fibra',
        'tarea_id',
        'empleado_id',
        'detalle_producto_id',
    ];
}
