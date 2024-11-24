<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HorarioLaboral extends Model
{
    use HasFactory;

    protected $table = 'rrhh_cp_horario_laboral';

    // Campos permitidos para inserción masiva
    protected $fillable = [
        'hora_entrada',
        'hora_salida',
    ];

}
