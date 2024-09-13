<?php

namespace App\Models\RecursosHumanos\NominaPrestamos;

use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;
/**
 * App\Models\RecursosHumanos\NominaPrestamos\Rubros
 *
 * @property int $id
 * @property string $nombre_rubro
 * @property string $valor_rubro
 * @property bool $es_porcentaje
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @method static \Illuminate\Database\Eloquent\Builder|Rubros acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Rubros filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Rubros ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Rubros newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Rubros newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Rubros query()
 * @method static \Illuminate\Database\Eloquent\Builder|Rubros setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Rubros setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Rubros setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|Rubros whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Rubros whereEsPorcentaje($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Rubros whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Rubros whereNombreRubro($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Rubros whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Rubros whereValorRubro($value)
 * @mixin \Eloquent
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
    private static $whiteListFilter = [
        'id',
        'valor_rubro',
        'es_porcentaje',
    ];
    protected $casts = [
        'es_porcentaje'=> 'boolean'
    ];
}
