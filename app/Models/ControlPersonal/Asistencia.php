<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asistencia extends Model
{
    use HasFactory;

    protected $table = 'rrhh_cp_asistencias'; // Nombre de la tabla

    // Campos permitidos para inserción masiva
    protected $fillable = [
        'empleado_id',
        'hora_ingreso',
        'hora_salida',
        'hora_salida_almuerzo',
        'hora_entrada_almuerzo',
    ];

    // Relación con la tabla empleados
    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }
}
