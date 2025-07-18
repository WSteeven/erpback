<?php

namespace App\Models\FondosRotativos\Gasto;

use Eloquent;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Models\Audit;

/**
 * App\Models\FondosRotativos\Gasto\TipoFondo
 *
 * @property int $id
 * @property string $descripcion
 * @property string $transcriptor
 * @property string $fecha_trans
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @method static Builder|TipoFondo acceptRequest(?array $request = null)
 * @method static Builder|TipoFondo filter(?array $request = null)
 * @method static Builder|TipoFondo ignoreRequest(?array $request = null)
 * @method static Builder|TipoFondo newModelQuery()
 * @method static Builder|TipoFondo newQuery()
 * @method static Builder|TipoFondo query()
 * @method static Builder|TipoFondo setBlackListDetection(?array $black_list_detections = null)
 * @method static Builder|TipoFondo setCustomDetection(?array $object_custom_detect = null)
 * @method static Builder|TipoFondo setLoadInjectedDetection($load_default_detection)
 * @method static Builder|TipoFondo whereCreatedAt($value)
 * @method static Builder|TipoFondo whereDescripcion($value)
 * @method static Builder|TipoFondo whereFechaTrans($value)
 * @method static Builder|TipoFondo whereId($value)
 * @method static Builder|TipoFondo whereTranscriptor($value)
 * @method static Builder|TipoFondo whereUpdatedAt($value)
 * @mixin Eloquent
 */
class TipoFondo extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;
    //use AuditableModel;
    protected $table = 'tipo_fondo';
    protected $primaryKey = 'id';
    protected $fillable = [
        'descripcion',
        'transcriptor',
        'fecha_trans',
    ];
    private static array $whiteListFilter = [
        'descripcion',
    ];
}
