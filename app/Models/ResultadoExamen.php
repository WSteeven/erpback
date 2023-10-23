<?php

namespace App\Models;

use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;

class ResultadoExamen extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, AuditableModel;

    protected $table = 'med_resultados_examenes';
    protected $fillable = [
        'resultados',
        'url_certificado',
        'empleado_examen_id',
    ];

    // Relaciones
    public function empleadoExamen()
    {
        return $this->belongsTo(EmpleadoExamen::class);
    }
}
