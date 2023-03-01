<?php

namespace App\Models\FondosRotativos\Gasto;

use App\Models\FondosRotativos\Usuario\Estatus;
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
        'id_detalle_viatico',
        'descripcion',
        'autorizacion',
        'id_estatus',
        'transcriptor',
        'fecha_trans',
    ];

    private static $whiteListFilter = [
        'descripcion',
    ];
    public function detalle(){
        return $this->hasOne(DetalleViatico::class, 'id', 'id_detalle_viatico');
    }
    public function estatus()
    {
        return $this->hasOne(Estatus::class, 'id','id_estatus');
    }
}
