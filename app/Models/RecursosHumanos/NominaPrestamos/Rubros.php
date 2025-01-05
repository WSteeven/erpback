<?php

namespace App\Models\RecursosHumanos\NominaPrestamos;

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
 * App\Models\RecursosHumanos\NominaPrestamos\Rubros
 *
 * @property int $id
 * @property string $nombre_rubro
 * @property string $valor_rubro
 * @property bool $es_porcentaje
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @method static Builder|Rubros acceptRequest(?array $request = null)
 * @method static Builder|Rubros filter(?array $request = null)
 * @method static Builder|Rubros ignoreRequest(?array $request = null)
 * @method static Builder|Rubros newModelQuery()
 * @method static Builder|Rubros newQuery()
 * @method static Builder|Rubros query()
 * @method static Builder|Rubros setBlackListDetection(?array $black_list_detections = null)
 * @method static Builder|Rubros setCustomDetection(?array $object_custom_detect = null)
 * @method static Builder|Rubros setLoadInjectedDetection($load_default_detection)
 * @method static Builder|Rubros whereCreatedAt($value)
 * @method static Builder|Rubros whereEsPorcentaje($value)
 * @method static Builder|Rubros whereId($value)
 * @method static Builder|Rubros whereNombreRubro($value)
 * @method static Builder|Rubros whereUpdatedAt($value)
 * @method static Builder|Rubros whereValorRubro($value)
 * @mixin Eloquent
 */
class Rubros extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;
    protected $table = 'rubros';
    protected $fillable = [
        'nombre_rubro',
        'valor_rubro',
        'es_porcentaje',
    ];

    const IESS = 1;
    const SBU = 2;
    private static array $whiteListFilter = [
        'id',
        'valor_rubro',
        'es_porcentaje',
    ];
    protected $casts = [
        'es_porcentaje'=> 'boolean'
    ];
}
