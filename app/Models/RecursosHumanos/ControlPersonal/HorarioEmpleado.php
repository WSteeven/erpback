<?php

namespace App\Models\RecursosHumanos\ControlPersonal;

use App\Models\Empleado;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HorarioEmpleado extends Model
{
    use HasFactory;

    protected $table = 'rrhh_cp_horarios_empleados';
    protected $fillable = [
        'empleado_id',
        'horario_id',
        'fecha_inicio',
        'fecha_fin',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }

    public function horarioLaboral()
    {
        return $this->belongsTo(HorarioLaboral::class, 'horario_id');
    }


}
