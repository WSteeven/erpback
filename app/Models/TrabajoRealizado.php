<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use App\Traits\UppercaseValuesTrait;

class TrabajoRealizado extends Model implements Auditable
{
    use HasFactory, AuditableModel, Filterable, UppercaseValuesTrait;

    protected $table = 'trabajos_realizados';
    protected $fillable = [
        'trabajo_realizado',
        'fotografia',
        'seguimiento_id',
    ];

    private static $whiteListFilter = [
        '*',
    ];

    // Relacion uno a muchos (inversa)
    public function emergencia()
    {
        return $this->belongsTo(Emergencia::class);
    }
}
