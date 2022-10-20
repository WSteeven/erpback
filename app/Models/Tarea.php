<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class Tarea extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;

    protected $table = "tareas";
    protected $fillable = [
        'codigo_tarea_jp',
        'codigo_tarea_cliente',
        'fecha_solicitud',
        'hora_solicitud',
        'coordinador_id',
        'es_proyecto',
        'codigo_proyecto',
        'cliente_id',
        'cliente_final_id',
        'detalle',
        'estado',
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
