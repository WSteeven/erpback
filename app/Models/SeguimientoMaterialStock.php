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

    /*************
     * Relaciones
     *************/
    public function subtarea()
    {
        return $this->hasOne(Subtarea::class, 'id', 'subtarea_id');
    }

    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }

    public function detalleProducto()
    {
        return $this->belongsTo(DetalleProducto::class);
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function scopeResponsable($query)
    {
        return $query->where('empleado_id', Auth::user()->empleado->id);
    }
}
