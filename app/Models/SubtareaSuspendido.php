<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class SubtareaSuspendido22 extends Model implements Auditable
{
    use HasFactory, AuditableModel;
    public $timestamps = false;
    protected $table = "subtarea_suspendido";

    protected $fillable = [
        'fecha_hora_suspendido',
        'motivo_suspendido_id',
        'subtarea_id'
    ];

    public function motivoSuspendido()
    {
        return $this->belongsTo(MotivoSuspendido::class);
    }
}
