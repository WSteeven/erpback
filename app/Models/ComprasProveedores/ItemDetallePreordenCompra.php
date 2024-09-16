<?php

namespace App\Models\ComprasProveedores;

use App\Models\DetalleProducto;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ComprasProveedores\ItemDetallePreordenCompra
 *
 * @property int $id
 * @property int $detalle_id
 * @property int $preorden_id
 * @property int $cantidad
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read DetalleProducto $detalle
 * @property-read \App\Models\ComprasProveedores\PreordenCompra $preorden
 * @method static \Illuminate\Database\Eloquent\Builder|ItemDetallePreordenCompra newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ItemDetallePreordenCompra newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ItemDetallePreordenCompra query()
 * @method static \Illuminate\Database\Eloquent\Builder|ItemDetallePreordenCompra whereCantidad($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ItemDetallePreordenCompra whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ItemDetallePreordenCompra whereDetalleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ItemDetallePreordenCompra whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ItemDetallePreordenCompra wherePreordenId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ItemDetallePreordenCompra whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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
