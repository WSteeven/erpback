<?php

namespace App\Models\FondosRotativos\Gasto;

use App\Models\FondosRotativos\Gasto\GastoCoordinador;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class DetalleMotivoGasto extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;
    protected $table='detalle_motivo_gastos';
    protected $fillable = [
        'id_motivo_gasto',
        'id_gasto_coordinador'
    ];
    private static $whiteListFilter = ['motivo_gasto', 'gasto_coordinador'];

}
