<?php

namespace App\Models\FondosRotativos\Viatico;

use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class Viatico extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;
    protected $table = 'viaticos';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id_lugar',
        'fecha_viat',
        'num_tarea',
        'ruc',
        'factura',
        'proveedor',
        'aut_especial',
        'detalle',
        'sub_detalle',
        'cant',
        'valor_u',
        'total',
        'comprobante',
        'comprobante2',
        'observacion',
        'id_usuario',
        'detalle_estado',
        'fecha_ingreso',
        'fecha_proc',
        'transcriptor',
        'fecha_trans'

    ];

    private static $whiteListFilter = [
        'factura',
    ];
    public function detalles()
    {
        return $this->hasOne(DetalleViatico::class, 'id', 'detalle');
    }
}
