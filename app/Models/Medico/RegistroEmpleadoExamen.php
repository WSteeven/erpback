<?php

namespace App\Models\Medico;

use App\Models\Empleado;
use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;

class RegistroEmpleadoExamen extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, AuditableModel;

    // Contantes
    const INGRESO = 'INGRESO';
    const OCUPACIONALES = 'OCUPACIONALES';
    const REINGRESO = 'REINGRESO';
    const SALIDA = 'SALIDA';

    protected $table = 'med_registros_empleados_examenes';
    protected $fillable = [
        'numero_registro',
        'observacion',
        'tipo_examen_id',
        'empleado_id',
    ];

    // Relaciones
    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }
}
