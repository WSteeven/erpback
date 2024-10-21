<?php

namespace App\Models\ComprasProveedores;

use App\Models\UnidadMedida;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

/**
 * App\Models\ComprasProveedores\ItemDetalleProforma
 *
 * @property int $id
 * @property int $proforma_id
 * @property int $unidad_medida_id
 * @property string $descripcion
 * @property int $cantidad
 * @property int $porcentaje_descuento
 * @property float $descuento
 * @property int $facturable
 * @property int $grava_iva
 * @property float $precio_unitario
 * @property float $iva
 * @property float $subtotal
 * @property float $total
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\ComprasProveedores\Proforma $proforma
 * @property-read UnidadMedida $unidadMedida
 * @method static \Illuminate\Database\Eloquent\Builder|ItemDetalleProforma newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ItemDetalleProforma newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ItemDetalleProforma query()
 * @method static \Illuminate\Database\Eloquent\Builder|ItemDetalleProforma whereCantidad($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ItemDetalleProforma whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ItemDetalleProforma whereDescripcion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ItemDetalleProforma whereDescuento($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ItemDetalleProforma whereFacturable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ItemDetalleProforma whereGravaIva($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ItemDetalleProforma whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ItemDetalleProforma whereIva($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ItemDetalleProforma wherePorcentajeDescuento($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ItemDetalleProforma wherePrecioUnitario($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ItemDetalleProforma whereProformaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ItemDetalleProforma whereSubtotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ItemDetalleProforma whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ItemDetalleProforma whereUnidadMedidaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ItemDetalleProforma whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ItemDetalleProforma extends Model implements Auditable
{
    use HasFactory, AuditableModel;
    public $table = 'cmp_item_detalle_proforma';
    public $fillable = [
        'proforma_id',
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
    public function proforma(){
        return $this->belongsTo(Proforma::class);
    }

    /**
     * Relacion uno a muchos
     */
    public function unidadMedida(){
        return $this->belongsTo(UnidadMedida::class);
    }

}
