<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subtarea extends Model
{
    use HasFactory;

    protected $table = "subtareas";
    protected $fillable = [
        'codigo_subtarea',
        'detalle',
        'actividad_realizada',
        'novedades',
        'fiscalizador',
        'ing_soporte',
        'ing_instalacion',
        'tipo_instalacion',
        'id_servicio',
        'ticket_phoenix',
        'tipo_tarea_id',
        'tarea_id',
    ];

    // Relacion uno a muchos (inversa)
    public function tarea()
    {
        return $this->belongsTo(Tarea::class);
    }

    // Relacion uno a muchos (inversa)
    public function tipo_tarea()
    {
        return $this->belongsTo(TipoTarea::class);
    }
}
