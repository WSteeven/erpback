<?php

namespace App\Models\FondosRotativos;

use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Audit;
use OwenIt\Auditing\Contracts\Auditable;

class Valija extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;
    use UppercaseValuesTrait;

    protected $table = 'fr_valijas';
    protected $fillable = [
        'gasto_id',
        'empleado_id',
        'departamento_id',
        'descripcion',
        'destinatario_id',
        'imagen_evidencia',
    ];

}
