<?php

namespace App\Models\ComprasProveedores;

use App\Models\UnidadMedida;
use App\Traits\UppercaseValuesTrait;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Models\Audit;

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
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @property-read UnidadMedida|null $unidadMedida
 * @method static Builder|ItemDetalleOrdenCompra newModelQuery()
 * @method static Builder|ItemDetalleOrdenCompra newQuery()
 * @method static Builder|ItemDetalleOrdenCompra query()
 * @method static Builder|ItemDetalleOrdenCompra whereCantidad($value)
 * @method static Builder|ItemDetalleOrdenCompra whereCreatedAt($value)
 * @method static Builder|ItemDetalleOrdenCompra whereDescripcion($value)
 * @method static Builder|ItemDetalleOrdenCompra whereDescuento($value)
 * @method static Builder|ItemDetalleOrdenCompra whereFacturable($value)
 * @method static Builder|ItemDetalleOrdenCompra whereGravaIva($value)
 * @method static Builder|ItemDetalleOrdenCompra whereId($value)
 * @method static Builder|ItemDetalleOrdenCompra whereIva($value)
 * @method static Builder|ItemDetalleOrdenCompra whereOrdenCompraId($value)
 * @method static Builder|ItemDetalleOrdenCompra wherePorcentajeDescuento($value)
 * @method static Builder|ItemDetalleOrdenCompra wherePrecioUnitario($value)
 * @method static Builder|ItemDetalleOrdenCompra whereProductoId($value)
 * @method static Builder|ItemDetalleOrdenCompra whereSubtotal($value)
 * @method static Builder|ItemDetalleOrdenCompra whereTotal($value)
 * @method static Builder|ItemDetalleOrdenCompra whereUnidadMedidaId($value)
 * @method static Builder|ItemDetalleOrdenCompra whereUpdatedAt($value)
 * @mixin Eloquent
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
