<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ControlAsistencia extends Model
{
    use HasFactory;

    protected $table = "control_asistencias";
    protected $fillable = [
        'codigo_tarea_jp',
        'codigo_tarea_cliente',
        'fecha_solicitud',
        'fecha_inicio',
        'fecha_finalizacion',
        'solicitante',
        'correo_solicitante',
        'detalle',
        'es_proyecto',
        'codigo_proyecto',
        'cliente_id',
    ];
}
