<?php

namespace App\Models;

use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;

class EmpleadoExamen extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, AuditableModel;

    protected $table = 'med_empleado_examen';
    protected $fillable = [
        'estado_examen_id',
        'registro_examen_id',
        'examen_id',
    ];

    // Relaciones
    public function estadoExamen()
    {
        return $this->belongsTo(EstadoExamen::class);
    }

    public function registroExamen()
    {
        return $this->belongsTo(RegistroExamen::class);
    }

    public function examen()
    {
        return $this->belongsTo(Examen::class);
    }

    public function resultadoExamen()
    {
        return $this->hasOne(ResultadoExamen::class);
    }
}
