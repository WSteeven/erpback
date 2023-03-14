<?php

namespace App\Models\FondosRotativos\Gasto;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class SubdetalleGasto extends Model
{
    use HasFactory;
    use AuditableModel;
    use Filterable;
    protected $table = 'gastos';
    protected $primaryKey = 'id';
    protected $fillable = [
        'gasto_id',
        'subdetalle_gasto_id'
    ];
    private static $whiteListFilter = ['gasto_id', 'subdetalle_gasto_id'];
    public function sub_detalle_info()
    {
        return $this->hasOne(SubDetalleViatico::class, 'id', 'sub_detalle');
    }
}
