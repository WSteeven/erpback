<?php

namespace App\Models\FondosRotativos\Gasto;

use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

/**
 * App\Models\FondosRotativos\Gasto\SubdetalleGasto
 *
 * @property int $id
 * @property int $gasto_id
 * @property int $subdetalle_gasto_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\FondosRotativos\Gasto\SubDetalleViatico|null $subDetalle
 * @method static \Illuminate\Database\Eloquent\Builder|SubdetalleGasto acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|SubdetalleGasto filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|SubdetalleGasto ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|SubdetalleGasto newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SubdetalleGasto newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SubdetalleGasto query()
 * @method static \Illuminate\Database\Eloquent\Builder|SubdetalleGasto setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|SubdetalleGasto setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|SubdetalleGasto setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|SubdetalleGasto whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubdetalleGasto whereGastoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubdetalleGasto whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubdetalleGasto whereSubdetalleGastoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubdetalleGasto whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class SubdetalleGasto extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use UppercaseValuesTrait;
    use Filterable;
    protected $table = 'subdetalle_gastos';
    protected $primaryKey = 'id';
    protected $fillable = [
        'gasto_id',
        'subdetalle_gasto_id'
    ];
    private static $whiteListFilter = ['gasto_id', 'subdetalle_gasto_id'];
    public function subDetalle()
    {
        return $this->hasOne(SubDetalleViatico::class, 'id', 'sub_detalle');
    }
}
