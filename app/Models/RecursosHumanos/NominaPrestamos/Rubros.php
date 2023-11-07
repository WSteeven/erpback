<?php

namespace App\Models\RecursosHumanos\NominaPrestamos;

use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;
class Rubros extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;
    protected $table = 'rubros';
    protected $fillable = [
        'nombre_rubro',
        'valor_rubro',
        'es_porcentaje',
    ];
    private static $whiteListFilter = [
        'id',
        'valor_rubro',
        'es_porcentaje',
    ];
    protected $casts = [
        'es_porcentaje'=> 'boolean'
    ];
}
