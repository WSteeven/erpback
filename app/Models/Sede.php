<?php

namespace App\Models;

use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;


class Sede extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;
    protected $table = 'sedes';
    protected $fillable = [
        'nombre'
    ];

    private static $whiteListFilter = [
        'id',
        'nombre',
    ];
}
