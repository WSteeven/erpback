<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class EmpleadoSubtarea extends Model implements Auditable
{
    use HasFactory, AuditableModel;
    protected $table = 'empleado_subtarea';
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
