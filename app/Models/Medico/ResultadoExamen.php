<?php

namespace App\Models\Medico;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use App\Traits\UppercaseValuesTrait;

class ResultadoExamen extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, AuditableModel;

    protected $table = 'med_configuraciones_examenes';
    protected $fillable = [
        'resultado',
        'fecha_examen',
        'configuracion_examen_id',
        'empleado_id',
    ];
}
