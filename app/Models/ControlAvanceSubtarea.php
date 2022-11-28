<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ControlAvanceSubtarea extends Model
{
    use HasFactory;

    protected $table = "control_avance_subtareas";
    protected $fillable = [
        'fecha_hora',
        'actividad',
        'observacion',
        'subtarea_id',
    ];
}
