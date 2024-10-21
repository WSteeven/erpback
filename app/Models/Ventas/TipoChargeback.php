<?php

namespace App\Models\Ventas;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;

/**
 * App\Models\Ventas\TipoChargeback
 *
 * @property int $id
 * @property string $nombre
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @method static \Illuminate\Database\Eloquent\Builder|TipoChargeback acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoChargeback filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoChargeback ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoChargeback newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TipoChargeback newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TipoChargeback query()
 * @method static \Illuminate\Database\Eloquent\Builder|TipoChargeback setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoChargeback setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoChargeback setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoChargeback whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoChargeback whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoChargeback whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoChargeback whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class TipoChargeback extends Model  implements Auditable
{
    use HasFactory;
    use AuditableModel, UppercaseValuesTrait, Filterable;
    protected $table = 'ventas_tipos_chargebacks';
    protected $fillable =['nombre'];
    private static $whiteListFilter = [
        '*',
    ];

}
