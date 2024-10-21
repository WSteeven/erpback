<?php

namespace App\Models\FondosRotativos\Gasto;

use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

/**
 * App\Models\FondosRotativos\Gasto\TipoFondo
 *
 * @property int $id
 * @property string $descripcion
 * @property string $transcriptor
 * @property string $fecha_trans
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @method static \Illuminate\Database\Eloquent\Builder|TipoFondo acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoFondo filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoFondo ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoFondo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TipoFondo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TipoFondo query()
 * @method static \Illuminate\Database\Eloquent\Builder|TipoFondo setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoFondo setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoFondo setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoFondo whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoFondo whereDescripcion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoFondo whereFechaTrans($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoFondo whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoFondo whereTranscriptor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoFondo whereUpdatedAt($value)
 * @mixin \Eloquent
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
    private static $whiteListFilter = [
        'descripcion',
    ];
}
