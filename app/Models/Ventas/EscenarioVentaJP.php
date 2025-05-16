<?php

namespace App\Models\Ventas;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;


/**
 * App\Models\Ventas\EscenarioVentaJP
 *
 * @property int $id
 * @property int $numero_mes
 * @property string|null $mes
 * @property int $vendedores
 * @property int $productividad_minima
 * @property int $vendedores_acumulados
 * @property string $total_ventas_adicionales
 * @property string $arpu_prom
 * @property int $altas
 * @property int $bajas
 * @property int $neta
 * @property int $stock
 * @property int $stock_que_factura
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @method static \Illuminate\Database\Eloquent\Builder|EscenarioVentaJP acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|EscenarioVentaJP filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|EscenarioVentaJP ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|EscenarioVentaJP newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EscenarioVentaJP newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EscenarioVentaJP query()
 * @method static \Illuminate\Database\Eloquent\Builder|EscenarioVentaJP setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|EscenarioVentaJP setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|EscenarioVentaJP setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|EscenarioVentaJP whereAltas($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EscenarioVentaJP whereArpuProm($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EscenarioVentaJP whereBajas($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EscenarioVentaJP whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EscenarioVentaJP whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EscenarioVentaJP whereMes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EscenarioVentaJP whereNeta($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EscenarioVentaJP whereNumeroMes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EscenarioVentaJP whereProductividadMinima($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EscenarioVentaJP whereStock($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EscenarioVentaJP whereStockQueFactura($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EscenarioVentaJP whereTotalVentasAdicionales($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EscenarioVentaJP whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EscenarioVentaJP whereVendedores($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EscenarioVentaJP whereVendedoresAcumulados($value)
 * @mixin \Eloquent
 */
class EscenarioVentaJP extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel, UppercaseValuesTrait, Filterable;
    protected $table = 'ventas_escenarios_ventas_jp';
    protected $fillable = [
        'mes',
        'vendedores',
        'productividad_minima',
        'vendedores_acumulados',
        'total_ventas_adicionales',
        'arpu_prom',
        'altas',
        'bajas',
        'neta',
        'stock',
        'stock_que_factura'
    ];
    private static $whiteListFilter = [
        '*',
    ];
}
