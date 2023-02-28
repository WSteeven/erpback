<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class ControlMaterialSubtarea extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    
    protected $table = 'control_materiales_subtareas';
    protected $fillable = [
        'stock_actual',
        'cantidad_utilizada',
        'fecha',
        'tarea_id',
        'subtarea_id',
        'detalle_producto_id',
        'grupo_id',
    ];
}
