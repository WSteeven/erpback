<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use OwenIt\Auditing\Auditable as AuditableModel;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UppercaseValuesTrait;
use OwenIt\Auditing\Contracts\Auditable;

class MotivoPausaTicket extends Model implements Auditable
{
    use HasFactory, AuditableModel, Filterable, UppercaseValuesTrait;
    protected $table = 'motivos_pausas_tickets';
    protected $fillable = ['motivo', 'activo'];
    protected $casts = ['activo' => 'boolean'];
    private static $whiteListFilter = [
        '*',
    ];
}
