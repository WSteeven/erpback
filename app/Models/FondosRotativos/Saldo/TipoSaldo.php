<?php

namespace App\Models\FondosRotativos\Saldo;

use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

/**
 * App\Models\FondosRotativos\Saldo\TipoSaldo
 *
 * @property int $id
 * @property string $descripcion
 * @property int $id_estatus
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @method static \Illuminate\Database\Eloquent\Builder|TipoSaldo acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoSaldo filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoSaldo ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoSaldo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TipoSaldo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TipoSaldo query()
 * @method static \Illuminate\Database\Eloquent\Builder|TipoSaldo setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoSaldo setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoSaldo setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoSaldo whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoSaldo whereDescripcion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoSaldo whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoSaldo whereIdEstatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoSaldo whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class TipoSaldo extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;
    protected $table = 'tipo_saldo';
    protected $primaryKey = 'id';
    protected $fillable = [
        'descripcion',
        'transcriptor',
        'fecha_trans',
    ];
    private static $whiteListFilter = [
        'descripcion',
    ];
}
