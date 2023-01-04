<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialGrupoTarea extends Model
{
    use HasFactory;

    protected $table = 'materiales_grupos_tareas';

    protected $fillable = [
        'cantidad_stock',
        'es_fibra',
        'tarea_id',
        'grupo_id',
        'detalle_producto_id',
    ];
}
