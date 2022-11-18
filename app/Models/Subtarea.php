<?php

namespace App\Models;

use App\Http\Resources\EmpleadoResource;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;

class Subtarea extends Model implements Auditable
{
    use HasFactory, AuditableModel, Filterable;

    const CREADO = 'CREADO';
    
    const ASIGNADO = 'ASIGNADO';
    const EJECUTANDO = 'EJECUTANDO';
    const PAUSADO = 'PAUSADO';
    
    const SUSPENDIDO = 'SUSPENDIDO';
    const CANCELADO = 'CANCELADO';
    const REALIZADO = 'REALIZADO';

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
    public function grupo()
    {
        return $this->belongsTo(Grupo::class);
    }

    // Relacion uno a muchos (inversa)
    public function tipo_trabajo()
    {
        return $this->belongsTo(TipoTrabajo::class);
    }

    /**
     * Relación uno a muchos .
     * Una subtarea puede tener varias transacciones
     */
    public function transacciones()
    {
        $this->hasMany(TransaccionBodega::class);
    }

    public function tecnicosPrincipales(array $ids)
    {
        // return EmpleadoResource::collection(Empleado::whereIn('id', $ids)->get());
        return Empleado::whereIn('id', $ids)->get()->map(fn($item) => [
            'id' => $item->id,
            'identificacion' => $item->identificacion,
            'nombres' => $item->nombres,
            'apellidos' => $item->apellidos,
            'telefono' => $item->telefono,
            'fecha_nacimiento' => $item->fecha_nacimiento,
            'email' => $item->user ? $item->user->email : '',
            'jefe' => $item->jefe ? $item->jefe->nombres . ' ' . $item->jefe->apellidos : 'N/A',
            'usuario' => $item->user->name,
            'sucursal' => $item->sucursal->lugar,
            'estado' => $item->estado,
            'grupo' => $item->grupo?->nombre,
            'disponible' => $item->disponible,
            'roles' => implode(', ', $item->user->getRoleNames()->toArray()),
        ]);
    }
}
