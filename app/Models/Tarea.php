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
        'codigo_tarea',
        'codigo_tarea_cliente',
        'fecha_solicitud',
        'hora_solicitud',
        'coordinador_id',
        'supervisor_id',
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
    public function supervisor()
    {
        return $this->belongsTo(Empleado::class, 'supervisor_id', 'id');
    }

     // Relacion uno a muchos (inversa)
     public function clienteFinal()
     {
         return $this->belongsTo(ClienteFinal::class);
     }

    // Relacion uno a muchos (inversa)
    public function coordinador()
    {
        return $this->belongsTo(Empleado::class);
    }

    /**
     * Relación uno a muchos .
     * Una tarea puede tener varias transacciones
     */
    public function transacciones(){
        $this->hasMany(TransaccionBodega::class);
    }
}
