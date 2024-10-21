<?php

namespace App\Models\ComprasProveedores;

use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

/**
 * App\Models\ComprasProveedores\ItemPagoProveedores
 *
 * @property int $id
 * @property int $pago_proveedor_id
 * @property string $proveedor
 * @property string $razon_social
 * @property string $tipo_documento
 * @property string $num_documento
 * @property string $fecha_emision
 * @property string $fecha_vencimiento
 * @property string|null $centro_costo
 * @property string|null $plazo
 * @property float|null $total
 * @property string|null $descripcion
 * @property float $valor_documento
 * @property float $retenciones
 * @property float $pagos
 * @property float $valor_pagar
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\ComprasProveedores\PagoProveedores|null $pago
 * @method static \Illuminate\Database\Eloquent\Builder|ItemPagoProveedores acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ItemPagoProveedores filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ItemPagoProveedores ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ItemPagoProveedores newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ItemPagoProveedores newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ItemPagoProveedores query()
 * @method static \Illuminate\Database\Eloquent\Builder|ItemPagoProveedores setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ItemPagoProveedores setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ItemPagoProveedores setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|ItemPagoProveedores whereCentroCosto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ItemPagoProveedores whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ItemPagoProveedores whereDescripcion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ItemPagoProveedores whereFechaEmision($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ItemPagoProveedores whereFechaVencimiento($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ItemPagoProveedores whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ItemPagoProveedores whereNumDocumento($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ItemPagoProveedores wherePagoProveedorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ItemPagoProveedores wherePagos($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ItemPagoProveedores wherePlazo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ItemPagoProveedores whereProveedor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ItemPagoProveedores whereRazonSocial($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ItemPagoProveedores whereRetenciones($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ItemPagoProveedores whereTipoDocumento($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ItemPagoProveedores whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ItemPagoProveedores whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ItemPagoProveedores whereValorDocumento($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ItemPagoProveedores whereValorPagar($value)
 * @mixin \Eloquent
 */
class ItemPagoProveedores extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;
    use UppercaseValuesTrait;

    public $table = 'cmp_item_pago_proveedor';
    public $fillable = [
        'pago_proveedor_id',
        'proveedor',
        'razon_social',
        'tipo_documento',
        'num_documento',
        'fecha_emision',
        'fecha_vencimiento',
        'centro_costo',
        'plazo',
        'total',
        'descripcion',
        'valor_documento',
        'valor_pagar',
        'retenciones',
        'pagos',
    ];

    const POR_VENCER = 'POR VENCER';
    const TREINTA_DIAS = '30 DIAS';
    const SESENTA_DIAS = '60 DIAS';
    const NOVENTA_DIAS = '90 DIAS';
    const CIENTO_VEINTE_DIAS = '120 DIAS';
    const MAYOR_TIEMPO = 'MAYOR A 120 DIAS';

    /**
     * ______________________________________________________________________________________
     * RELACIONES CON OTRAS TABLAS
     * ______________________________________________________________________________________
     */
    public function pago()
    {
        return $this->belongsTo(PagoProveedores::class, 'id', 'pago_proveedor_id');
    }

    /**
     * ______________________________________________________________________________________
     * FUNCIONES
     * ______________________________________________________________________________________
     */
    
}
