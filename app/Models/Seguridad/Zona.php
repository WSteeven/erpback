<?php

namespace App\Models\Seguridad;

use App\Models\Empleado;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;

class Zona extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, Filterable, AuditableModel;

    protected $table = 'seg_zonas';
    protected $fillable = [
        'nombre',
        'descripcion',
        'direccion',
        'coordenadas',
        'activo',
    ];

    private static array $whiteListFilter = ['*'];
    protected $casts = [
        'activo' => 'boolean',
    ];

    public function empleados()
    {
        return $this->belongsToMany(Empleado::class, 'seg_miembros_zonas', 'zona_id', 'empleado_id')
            ->withTimestamps();
    }
}
