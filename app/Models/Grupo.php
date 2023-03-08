<?php

namespace App\Models;

use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;

class Grupo extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, Filterable, AuditableModel;

    protected $table = 'grupos';
    protected $fillable = ['nombre', 'activo'];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
        'activo' => 'boolean',
    ];

    private static $whiteListFilter = [
        'nombre',
    ];

    /*public function tareas()
    {
        return $this->belongsToMany(Tarea::class);
    }*/

    // eliminar
    /*public function subtareas()
    {
        return $this->belongsToMany(Subtarea::class);
    } */

    public function subtareas()
    {
        return $this->hasMany(Subtarea::class);
    }

    public function controlMaterialesSubtareas()
    {
        return $this->hasMany(ControlMaterialTrabajo::class);
    }
}
