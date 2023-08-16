<?php

namespace App\Models\ComprasProveedores;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemDetalleOrdenCompra extends Model
{
    use HasFactory;
    protected $table = 'cmp_item_detalle_orden_compra';

    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
        'facturable'=>'boolean',
        'grava_iva'=>'boolean',
    ];
    
}
