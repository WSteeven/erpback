<?php

namespace App\Models\FondosRotativos\Gasto;

use App\Models\FondosRotativos\Gasto\GastoCoordinador;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

/**
 * App\Models\FondosRotativos\Gasto\DetalleMotivoGasto
 *
 * @property int $id
 * @property int $id_motivo_gasto
 * @property int $id_gasto_coordinador
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleMotivoGasto acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleMotivoGasto filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleMotivoGasto ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleMotivoGasto newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleMotivoGasto newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleMotivoGasto query()
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleMotivoGasto setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleMotivoGasto setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleMotivoGasto setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleMotivoGasto whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleMotivoGasto whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleMotivoGasto whereIdGastoCoordinador($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleMotivoGasto whereIdMotivoGasto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleMotivoGasto whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class DetalleMotivoGasto extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;
    protected $table='detalle_motivo_gastos';
    protected $fillable = [
        'id_motivo_gasto',
        'id_gasto_coordinador'
    ];
    private static $whiteListFilter = ['motivo_gasto', 'gasto_coordinador'];

}
