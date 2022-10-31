<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;

class Subtarea extends Model implements Auditable
{
    use HasFactory, AuditableModel, Filterable;

    protected $table = "subtareas";
    protected $fillable = [
        'codigo_subtarea',
        'detalle',
        'grupo_id',
        'tipo_trabajo_id',
        'tarea_id',
        'fecha_hora_creacion',
        'fecha_hora_asignacion',
        'fecha_hora_inicio',
        'fecha_hora_finalizacion',
        'cantidad_dias',
        'fecha_hora_realizado',
        'fecha_hora_suspendido',
        'causa_suspencion',
        'fecha_hora_cancelacion',
        'causa_cancelacion',
        'es_dependiente',
        'subtarea_dependiente',
        'es_ventana',
        'hora_inicio_ventana',
        'hora_fin_ventana',
        'descripcion_completa',
        'tecnicos_grupo_principal',
        'tecnicos_otros_grupos',
        'estado',
    ];

    protected $casts = [
        'es_dependiente' => 'boolean',
        'es_ventana' => 'boolean',
    ];

    private static $whiteListFilter = [
        '*'
    ];

    // Relacion uno a muchos (inversa)
    public function tarea()
    {
        return $this->belongsTo(Tarea::class);
    }

    // Relacion uno a muchos (inversa)
    public function tipo_trabajo()
    {
        return $this->belongsTo(TipoTarea::class);
    }

    /**
     * Relación uno a muchos .
     * Una subtarea puede tener varias transacciones
     */
    public function transacciones(){
        $this->hasMany(TransaccionBodega::class);
    }
}
