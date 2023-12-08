<?php

namespace App\Models\ComprasProveedores;

use App\Models\UnidadMedida;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Audit;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class ItemDetallePrefactura extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;
    use UppercaseValuesTrait;

    public $table = 'cmp_item_detalle_prefactura';
    public $fillable = [
        'prefactura_id',
        'unidad_medida_id',
        'descripcion',
        'cantidad',
        'porcentaje_descuento',
        'descuento',
        'facturable',
        'grava_iva',
        'precio_unitario',
        'iva',
        'subtotal',
        'total',
    ];

    /**
     * ______________________________________________________________________________________
     * RELACIONES CON OTRAS TABLAS
     * ______________________________________________________________________________________
     */
    public function prefactura(){
        return $this->belongsTo(Prefactura::class);
    }

    /**
     * Relacion uno a muchos
     */
    public function unidadMedida(){
        return $this->belongsTo(UnidadMedida::class);
    }
}
