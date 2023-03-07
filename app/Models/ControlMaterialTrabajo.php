<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
}
