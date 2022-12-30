<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ControlMaterialSubtarea extends Model
{
    use HasFactory;
    protected $table = 'control_materiales_subtareas';
    protected $fillable = [
        'stock_actual',
        'cantidad_utilizada',
        'fecha',
        'tarea_id',
        'subtarea_id',
        'detalle_producto_id',
        'grupo_id',
    ];
}
