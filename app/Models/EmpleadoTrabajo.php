<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class EmpleadoTrabajo extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    protected $table = 'empleado_trabajo';
    protected $fillable = [
        'es_responsable',
        'empleado_id',
        'subtarea_id',
    ];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
        'es_responsable' => 'boolean',
    ];
}
