<?php

namespace App\Models\Medico;

use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;

class EsquemaVacuna extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, AuditableModel;

    protected $table = 'med_esquemas_vacunas';
    protected $fillable = [
        'nombre_vacuna',
        'dosis_totales',
        'dosis_aplicadas',
        'observacion',
        'registro_empleado_examen_id',
        'tipo_vacuna_id',

    ];

    // Relaciones
    public function registroEmpleadoExamen()
    {
        return $this->hasOne(RegistroEmpleadoExamen::class,'id','registro_examen_id');
    }
    public function tipoVacuna(){
        return $this->hasOne(TipoVacuna::class,'id','tipo_vacuna_id');
    }
}
