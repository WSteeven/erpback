<?php

namespace App\Models\ComprasProveedores;

use App\Traits\UppercaseItemsOrdenCompra;
use App\Traits\UppercaseValuesTrait;
use Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class ItemDetalleOrdenCompra extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait;
    use AuditableModel;

    protected $table = 'cmp_item_detalle_orden_compra';
    protected $fillable = [
        'orden_compra_id',
        'producto_id',
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

    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
        'facturable' => 'boolean',
        'grava_iva' => 'boolean',
    ];
    
}
