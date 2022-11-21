<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PausaSubtarea extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = "pausas_subtareas";

    protected $fillable = [
        'fecha_hora_pausa',
        'fecha_hora_retorno',
        'motivo',
    ];
}
