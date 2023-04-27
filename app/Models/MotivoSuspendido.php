<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use OwenIt\Auditing\Auditable as AuditableModel;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UppercaseValuesTrait;
use OwenIt\Auditing\Contracts\Auditable;

class MotivoSuspendido extends Model implements Auditable
{
    use HasFactory, AuditableModel, UppercaseValuesTrait;
    protected $table = 'motivos_suspendidos';
    protected $fillable = ['motivo', 'activo'];
    protected $casts = ['activo' => 'boolean'];

    public function subtarea()
    {
        return $this->belongsToMany(Subtarea::class)->withPivot('empleado_id')->withTimestamps();
    }
}
