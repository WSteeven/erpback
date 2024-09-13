<?php

namespace App\Models\Ventas;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;


/**
 * App\Models\Ventas\EsquemaComision
 *
 * @property int $id
 * @property int $mes_liquidacion
 * @property string $esquema_comision
 * @property string $tarifa_basica
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @method static \Illuminate\Database\Eloquent\Builder|EsquemaComision acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|EsquemaComision filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|EsquemaComision ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|EsquemaComision newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EsquemaComision newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EsquemaComision query()
 * @method static \Illuminate\Database\Eloquent\Builder|EsquemaComision setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|EsquemaComision setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|EsquemaComision setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|EsquemaComision whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EsquemaComision whereEsquemaComision($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EsquemaComision whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EsquemaComision whereMesLiquidacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EsquemaComision whereTarifaBasica($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EsquemaComision whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class EsquemaComision extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel, UppercaseValuesTrait, Filterable;
    protected $table = 'ventas_esquemas_comisiones';
    protected $fillable =['mes_liquidacion','esquema_comision','tarifa_basica'];
    private static $whiteListFilter = [
        '*',
    ];
}
