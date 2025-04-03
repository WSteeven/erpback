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

/**
 * App\Models\ComprasProveedores\ItemDetallePrefactura
 *
 * @property int $id
 * @property int $prefactura_id
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
 * @property-read \App\Models\ComprasProveedores\Prefactura $prefactura
 * @property-read UnidadMedida $unidadMedida
 * @method static \Illuminate\Database\Eloquent\Builder|ItemDetallePrefactura acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ItemDetallePrefactura filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ItemDetallePrefactura ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ItemDetallePrefactura newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ItemDetallePrefactura newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ItemDetallePrefactura query()
 * @method static \Illuminate\Database\Eloquent\Builder|ItemDetallePrefactura setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ItemDetallePrefactura setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ItemDetallePrefactura setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|ItemDetallePrefactura whereCantidad($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ItemDetallePrefactura whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ItemDetallePrefactura whereDescripcion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ItemDetallePrefactura whereDescuento($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ItemDetallePrefactura whereFacturable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ItemDetallePrefactura whereGravaIva($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ItemDetallePrefactura whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ItemDetallePrefactura whereIva($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ItemDetallePrefactura wherePorcentajeDescuento($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ItemDetallePrefactura wherePrecioUnitario($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ItemDetallePrefactura wherePrefacturaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ItemDetallePrefactura whereSubtotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ItemDetallePrefactura whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ItemDetallePrefactura whereUnidadMedidaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ItemDetallePrefactura whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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
