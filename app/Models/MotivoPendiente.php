<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use OwenIt\Auditing\Auditable as AuditableModel;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UppercaseValuesTrait;
use OwenIt\Auditing\Contracts\Auditable;

class MotivoPendiente extends Model implements Auditable
{
    use HasFactory, AuditableModel, UppercaseValuesTrait;
    protected $table = 'motivos_pendientes';
    protected $fillable = ['motivo', 'activo'];
    protected $casts = ['activo' => 'boolean'];
}
