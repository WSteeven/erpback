<?php

namespace App\Models;

use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;

class InformacionMedicaEmpleado extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, AuditableModel;

    protected $table = 'med_informacion_medica_empleados';
    protected $fillable = [
        'ficha_preocupacional',
        'ficha_aptitud',
        'ficha_ocupacional_periodico',
        'ficha_reingreso',
        'ficha_salida',
        'evaluacion_riesgo_psicosocial',
        'encuesta',
        'registro_examen_id',
    ];

    // Relaciones
    public function registroExamen()
    {
        return $this->belongsTo(RegistroExamen::class);
    }
}
