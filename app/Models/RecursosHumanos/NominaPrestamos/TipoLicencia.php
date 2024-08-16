<?php

namespace App\Models\RecursosHumanos\NominaPrestamos;

use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class TipoLicencia extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;
    protected $table = 'tipo_licencias';
    protected $fillable = [
        'nombre',
        'num_dias',
        'estado',
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
        'estado' => 'boolean',
    ];

    private static $whiteListFilter = [
        'id',
        'nombre',
        'estado',
    ];
}
