<?php

namespace App\Models\FondosRotativos\Viatico;

use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;
class SubDetalleViatico extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;
    protected $table = 'sub_detalle_viatico';
    protected $primaryKey = 'id';
    protected $fillable = [
        'descripcion',
        'autorizacion',
        'id_estatus',
        'transcriptor',
        'fecha_trans',
    ];

    private static $whiteListFilter = [
        'descripcion',
    ];
}
