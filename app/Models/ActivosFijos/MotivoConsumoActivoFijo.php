<?php

namespace App\Models\ActivosFijos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use App\Traits\UppercaseValuesTrait;

/**
 * App\Models\ActivosFijos\MotivoConsumoActivoFijo
 *
 * @property int $id
 * @property string $nombre
 * @property int $categoria_motivo_consumo_activo_fijo_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\ActivosFijos\CategoriaMotivoConsumoActivoFijo $categoriaMotivoConsumoActivoFijo
 * @method static \Illuminate\Database\Eloquent\Builder|MotivoConsumoActivoFijo acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|MotivoConsumoActivoFijo filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|MotivoConsumoActivoFijo ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|MotivoConsumoActivoFijo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MotivoConsumoActivoFijo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MotivoConsumoActivoFijo query()
 * @method static \Illuminate\Database\Eloquent\Builder|MotivoConsumoActivoFijo setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|MotivoConsumoActivoFijo setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|MotivoConsumoActivoFijo setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|MotivoConsumoActivoFijo whereCategoriaMotivoConsumoActivoFijoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MotivoConsumoActivoFijo whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MotivoConsumoActivoFijo whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MotivoConsumoActivoFijo whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MotivoConsumoActivoFijo whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class MotivoConsumoActivoFijo extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, Filterable, AuditableModel;

    protected $table = 'af_motivos_consumo_activos_fijos';
    protected $fillable = [
        'nombre',
        'categoria_motivo_consumo_activo_fijo_id',
    ];

    private static $whiteListFilter = ['*'];

    public function categoriaMotivoConsumoActivoFijo()
    {
        return $this->belongsTo(CategoriaMotivoConsumoActivoFijo::class);
    }
}
