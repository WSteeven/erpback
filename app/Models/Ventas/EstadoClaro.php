<?php

namespace App\Models\Ventas;

use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;

class EstadoClaro extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel, UppercaseValuesTrait, Filterable;

    protected $table = 'ventas_estados_claro';
    protected $fillable = [
        'nombre',
        'abreviatura',
        'tipo',
        'activo',
    ];
    protected $casts = [
        'activo' => 'boolean',
    ];

    private static array $whiteListFilter = ['*'];

}
