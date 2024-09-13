<?php

namespace App\Models\ComprasProveedores;

use App\Models\UnidadMedida;
use App\Traits\UppercaseItemsOrdenCompra;
use App\Traits\UppercaseValuesTrait;
use Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

/**
 * App\Models\ComprasProveedores\ItemDetalleOrdenCompra
 *
 * @property int $id
 * @property int $orden_compra_id
 * @property int $producto_id
 * @property string|null $descripcion
 * @property int|null $unidad_medida_id
 * @property int $cantidad
 * @property int $porcentaje_descuento
 * @property float $descuento
 * @property bool $facturable
 * @property bool $grava_iva
 * @property float $precio_unitario
 * @property float $iva
 * @property float $subtotal
 * @property float $total
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read UnidadMedida|null $unidadMedida
 * @method static \Illuminate\Database\Eloquent\Builder|ItemDetalleOrdenCompra newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ItemDetalleOrdenCompra newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ItemDetalleOrdenCompra query()
 * @method static \Illuminate\Database\Eloquent\Builder|ItemDetalleOrdenCompra whereCantidad($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ItemDetalleOrdenCompra whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ItemDetalleOrdenCompra whereDescripcion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ItemDetalleOrdenCompra whereDescuento($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ItemDetalleOrdenCompra whereFacturable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ItemDetalleOrdenCompra whereGravaIva($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ItemDetalleOrdenCompra whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ItemDetalleOrdenCompra whereIva($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ItemDetalleOrdenCompra whereOrdenCompraId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ItemDetalleOrdenCompra wherePorcentajeDescuento($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ItemDetalleOrdenCompra wherePrecioUnitario($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ItemDetalleOrdenCompra whereProductoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ItemDetalleOrdenCompra whereSubtotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ItemDetalleOrdenCompra whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ItemDetalleOrdenCompra whereUnidadMedidaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ItemDetalleOrdenCompra whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ItemDetalleOrdenCompra extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait;
    use AuditableModel;

    protected $table = 'cmp_item_detalle_orden_compra';
    protected $fillable = [
        'orden_compra_id',
        'producto_id',
        'descripcion',
        'unidad_medida_id',
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

    /**
     * Uno o varios items pertenecen a una categoría
     */
    public function unidadMedida()
    {
        return $this->belongsTo(UnidadMedida::class);
    }
}
