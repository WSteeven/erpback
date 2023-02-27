<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PausaTrabajo extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = "pausas_trabajos";

    protected $fillable = [
        'fecha_hora_pausa',
        'fecha_hora_retorno',
        'motivo',
        'trabajo_id'
    ];
}
