<?php

namespace App\Models\FondosRotativos\Gasto;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class MotivoGasto extends Model implements Auditable
{
    use HasFactory;
    use HasFactory;
    use AuditableModel;
    use Filterable;
    protected $table = 'motivo_gastos';
    protected $primaryKey = 'id';
    protected $fillable = ['nombre'];
    private static $whiteListFilter = ['nombre'];
}
