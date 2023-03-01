<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class ControlMaterialTrabajo extends Model implements Auditable
{
    use HasFactory, AuditableModel;

    protected $table = 'control_materiales_trabajos';
    protected $fillable = [
        'stock_actual',
        'cantidad_utilizada',
        'fecha',
        'tarea_id',
        'trabajo_id',
        'empleado_id',
        'grupo_id',
        'detalle_producto_id',
    ];

    public function scopeResponsable($query)
    {
        return $query->where('empleado_id', Auth::user()->empleado->id);
    }

    public function trabajo() //$query, $trabajo_id)
    {
        return $this->hasOne(Trabajo::class, 'id', 'trabajo_id');
        // return $query->where('trabajo_id', $trabajo_id);
    }
}
