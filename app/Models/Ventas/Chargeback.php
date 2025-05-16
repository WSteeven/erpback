<?php

namespace App\Models\Ventas;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use OwenIt\Auditing\Models\Audit;

/**
 * App\Models\Ventas\Chargeback
 *
 * @property int $id
 * @property int $venta_id
 * @property string $fecha
 * @property string $valor
 * @property int $id_tipo_chargeback
 * @property string|null $porcentaje
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @property-read TipoChargeback|null $tipo_chargeback
 * @method static Builder|Chargeback acceptRequest(?array $request = null)
 * @method static Builder|Chargeback filter(?array $request = null)
 * @method static Builder|Chargeback ignoreRequest(?array $request = null)
 * @method static Builder|Chargeback newModelQuery()
 * @method static Builder|Chargeback newQuery()
 * @method static Builder|Chargeback query()
 * @method static Builder|Chargeback setBlackListDetection(?array $black_list_detections = null)
 * @method static Builder|Chargeback setCustomDetection(?array $object_custom_detect = null)
 * @method static Builder|Chargeback setLoadInjectedDetection($load_default_detection)
 * @method static Builder|Chargeback whereCreatedAt($value)
 * @method static Builder|Chargeback whereFecha($value)
 * @method static Builder|Chargeback whereId($value)
 * @method static Builder|Chargeback whereIdTipoChargeback($value)
 * @method static Builder|Chargeback wherePorcentaje($value)
 * @method static Builder|Chargeback whereUpdatedAt($value)
 * @method static Builder|Chargeback whereValor($value)
 * @method static Builder|Chargeback whereVentaId($value)
 * @property-read \App\Models\Ventas\Venta|null $venta
 * @mixin Eloquent
 */
class Chargeback extends Model  implements Auditable
{
    use HasFactory;
    use AuditableModel, UppercaseValuesTrait, Filterable;
    protected $table = 'ventas_chargebacks';
    protected $fillable =['venta_id','fecha','valor','id_tipo_chargeback','porcentaje'];
    private static array $whiteListFilter = [
        '*',
    ];
    public function venta(){
        return $this->hasOne(Venta::class,'id','venta_id');
    }
    public function tipo_chargeback(){
        return $this->hasOne(TipoChargeback::class,'id','id_tipo_chargeback');
    }

}
