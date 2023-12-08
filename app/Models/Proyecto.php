<?php

namespace App\Models;

use App\Models\Tareas\Etapa;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UppercaseValuesTrait;
use Illuminate\Support\Facades\Auth;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;

class Proyecto extends Model implements Auditable
{
    use HasFactory, Filterable, UppercaseValuesTrait, AuditableModel;
    protected $table = "proyectos";

    protected $fillable = [
        'codigo_proyecto',
        'nombre',
        'cliente_id',
        'canton_id',
        'coordinador_id',
        'fiscalizador_id',
        'fecha_inicio',
        'fecha_fin',
        'finalizado',
    ];

    protected $casts = [
        'finalizado' => 'boolean',
    ];

    private static $whiteListFilter = [
        '*',
        'etapas.responsable_id',
    ];

    // Relacion uno a muchos (inversa)
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    // Relacion uno a muchos (inversa)
    public function coordinador()
    {
        return $this->belongsTo(Empleado::class, 'coordinador_id', 'id');
    }

    // Relacion uno a muchos (inversa)
    public function canton()
    {
        return $this->belongsTo(Canton::class);
    }
     /**
     * Relación uno a muchos.
     * Un proyecto tiene varias etapas
     */
    public function etapas()
    {
        return $this->hasMany(Etapa::class);
    }

    /*********
     * Scopes
     *********/
    public function scopePorCoordinador($query)
    {
        return $query->where('coordinador_id', Auth::user()->empleado->id);
    }
}
