<?php

namespace App\Models;

use App\Models\Tareas\CentroCosto;
use App\Models\Tareas\SubcentroCosto;
use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;

class Grupo extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, Filterable, AuditableModel;

    const R1 = 'R1';
    const R2 = 'R2';
    const R3 = 'R3';
    const R4 = 'R4';

    protected $table = 'grupos';
    protected $fillable = ['nombre', 'region', 'activo', 'coordinador_id'];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
        'activo' => 'boolean',
    ];

    private static $whiteListFilter = [
        'nombre',
        'activo',
        'coordinador_id',
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

    public function empleados(){
        return $this->hasMany(Empleado::class);
    }

    public function subtareas()
    {
        return $this->hasMany(Subtarea::class);
    }

    // public function controlMaterialesSubtareas()
    // {
    //     return $this->hasMany(ControlMaterialTrabajo::class);
    // }

    public function coordinador()
    {
        return $this->belongsTo(Empleado::class, 'coordinador_id', 'id');
    }
    public function subCentroCosto()
    {
        return $this->belongsTo(SubcentroCosto::class, 'id', 'grupo_id');
    }

}
