<?php

namespace App\Models\Tareas;

use App\Models\Empleado;
use App\Models\Grupo;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;

class Nodo extends Model implements Auditable
{
    use HasFactory;
    use UppercaseValuesTrait, Filterable;
    use AuditableModel;

    protected $table = 'tar_nodos';
    protected $fillable = [
        'grupos',
        'coordinador_id',
        'nombre',
        'activo',
    ];
    protected $casts = [
        'grupos' => 'array',
        'activo' => 'boolean',
    ];
    private static array $whiteListFilter = ['*'];


    public function coordinador()
    {
        return $this->belongsTo(Empleado::class);
    }

    public function grupos()
    {
        return $this->hasMany(Grupo::class);
    }
}
