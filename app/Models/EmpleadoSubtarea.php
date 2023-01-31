<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmpleadoSubtarea extends Model
{
    use HasFactory;
    protected $table = 'empleados_subtareas';
    protected $fillable = [
        'empleado_id',
        'subtarea_id',
    ];
}
