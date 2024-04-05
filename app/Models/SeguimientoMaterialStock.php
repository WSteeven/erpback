<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class SeguimientoMaterialStock extends Model implements Auditable
{
    use HasFactory, AuditableModel;

    protected $table = 'seguimientos_materiales_stock';
    protected $fillable = [
        'stock_actual',
        'cantidad_utilizada',
        'empleado_id',
        'subtarea_id',
        'detalle_producto_id',
        'cliente_id',
    ];

    public function scopeResponsable($query)
    {
        return $query->where('empleado_id', Auth::user()->empleado->id);
    }
}
