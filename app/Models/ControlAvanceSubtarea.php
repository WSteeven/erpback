<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class ControlAvanceSubtarea extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;

    protected $table = "control_avance_subtareas";
    protected $fillable = [
        'fecha_hora',
        'actividad',
        'observacion',
        'subtarea_id',
    ];
}
