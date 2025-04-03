<?php

namespace App\Models\ActivosFijos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use App\Traits\UppercaseValuesTrait;

/**
 * App\Models\ActivosFijos\CategoriaMotivoConsumoActivoFijo
 *
 * @property int $id
 * @property string $nombre
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @method static \Illuminate\Database\Eloquent\Builder|CategoriaMotivoConsumoActivoFijo acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoriaMotivoConsumoActivoFijo filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoriaMotivoConsumoActivoFijo ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoriaMotivoConsumoActivoFijo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CategoriaMotivoConsumoActivoFijo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CategoriaMotivoConsumoActivoFijo query()
 * @method static \Illuminate\Database\Eloquent\Builder|CategoriaMotivoConsumoActivoFijo setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoriaMotivoConsumoActivoFijo setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoriaMotivoConsumoActivoFijo setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoriaMotivoConsumoActivoFijo whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoriaMotivoConsumoActivoFijo whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoriaMotivoConsumoActivoFijo whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoriaMotivoConsumoActivoFijo whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CategoriaMotivoConsumoActivoFijo extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, Filterable, AuditableModel;

    protected $table = 'af_categorias_motivos_consumo_activos_fijos';
    protected $fillable = [
        'nombre',
    ];

    private static $whiteListFilter = ['*'];
}
