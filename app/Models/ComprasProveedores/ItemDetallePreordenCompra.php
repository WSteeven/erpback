<?php

namespace App\Models\ComprasProveedores;

use App\Models\DetalleProducto;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemDetallePreordenCompra extends Model
{
    use HasFactory;
    protected $table = 'cmp_item_detalle_preorden_compra';
    

    public function preorden(){
        return $this->belongsTo(PreordenCompra::class);
    }
    public function detalle(){
        return $this->belongsTo(DetalleProducto::class);
    }
}
