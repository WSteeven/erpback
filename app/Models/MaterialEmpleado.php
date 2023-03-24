<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Support\Facades\Auth;

class MaterialEmpleado extends Model
{
    use HasFactory, Filterable;

    protected $table = 'materiales_empleados';

    protected $fillable = [
        'cantidad_stock',
        'es_fibra',
        'empleado_id',
        'detalle_producto_id',
    ];

    private static $whiteListFilter = ['*'];

    public function scopeResponsable($query)
    {
        return $query->where('empleado_id', Auth::user()->empleado->id);
    }
}
