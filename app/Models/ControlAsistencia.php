<?php

namespace App\Models;

use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class ControlAsistencia extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use UppercaseValuesTrait;

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
