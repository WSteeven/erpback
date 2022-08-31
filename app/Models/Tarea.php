<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tarea extends Model
{
    use HasFactory;

    protected $table = "tareas";
    protected $fillable = [
        'codigo_tarea_jp',
        'codigo_tarea_cliente',
        'fecha_solicitud',
        'fecha_inicio',
        'fecha_finalizacion',
        'solicitante',
        'correo_solicitante',
        'detalle',
        'es_proyecto',
        'codigo_proyecto',
        'cliente_id',
        'coordinador_id',
    ];
    protected $casts = ['es_proyecto' => 'boolean'];

    // Relacion uno a muchos (inversa)
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    // Relacion uno a muchos (inversa)
    public function coordinador()
    {
        return $this->belongsTo(Empleado::class);
    }
}
