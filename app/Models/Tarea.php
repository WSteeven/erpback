<?php

namespace App\Models;

use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class Tarea extends Model implements Auditable
{
    use HasFactory;
    use Filterable;
    use AuditableModel;

    protected $table = "tareas";
    protected $fillable = [
        'codigo_tarea',
        'codigo_tarea_cliente',
        'fecha_solicitud',
        'coordinador_id',
        'supervisor_id',
        'es_proyecto',
        'codigo_proyecto',
        'cliente_id',
        'cliente_final_id',
        'detalle',
        'estado',
        'ubicacion_tarea_id',
    ];

    protected $casts = ['es_proyecto' => 'boolean'];

    private static $whiteListFilter = ['*'];

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
    public function coordinador()
    {
        return $this->belongsTo(Empleado::class);
    }

    /**
     * Relación uno a muchos .
     * Una tarea puede tener varias transacciones
     */
    public function transacciones()
    {
        return $this->hasMany(TransaccionBodega::class);
    }
    
    /**
     * Relación uno a muchos .
     * Una tarea puede una o varias devoluciones
     */
    public function devoluciones()
    {
        return $this->hasMany(Devolucion::class);
    }
    
    /**
     * Relación uno a muchos .
     * Una tarea puede uno o varios traspasos
     */
    public function traspasos()
    {
        return $this->hasMany(Traspaso::class);
    }


    // Relacion uno a uno (inversa)
    public function ubicacionTarea()
    {
        // return $this->belongsTo(UbicacionTarea::class);
        return $this->hasOne(UbicacionTarea::class);
    }

    // Relacion uno a uno (inversa)
    public function clienteFinal()
    {
        return $this->belongsTo(ClienteFinal::class);
    }

    public function subtareas()
    {
        return $this->hasMany(Subtarea::class);
    }

    public function esPrimeraAsignacion($subtarea_id)
    {
        $subtareaEncontrada = $this->subtareas()->where('fecha_hora_asignacion', '!=', null)->orderBy('fecha_hora_asignacion', 'asc')->first();
        return $subtareaEncontrada?->id == $subtarea_id;
    }
}
