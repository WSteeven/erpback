<?php

namespace App\Models\FondosRotativos\Gasto;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

/**
 * App\Models\FondosRotativos\Gasto\MotivoGasto
 *
 * @property int $id
 * @property string $nombre
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\FondosRotativos\Gasto\GastoCoordinador> $motivos
 * @property-read int|null $motivos_count
 * @method static \Illuminate\Database\Eloquent\Builder|MotivoGasto acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|MotivoGasto filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|MotivoGasto ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|MotivoGasto newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MotivoGasto newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MotivoGasto query()
 * @method static \Illuminate\Database\Eloquent\Builder|MotivoGasto setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|MotivoGasto setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|MotivoGasto setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|MotivoGasto whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MotivoGasto whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MotivoGasto whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MotivoGasto whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class MotivoGasto extends Model implements Auditable
{
    use HasFactory;
    use HasFactory;
    use AuditableModel;
    use Filterable;
    protected $table = 'motivo_gastos';
    protected $primaryKey = 'id';
    protected $fillable = ['nombre'];
    private static $whiteListFilter = ['nombre'];
    public function motivos(){
        return $this->belongsToMany(GastoCoordinador::class,'detalle_motivo_gastos','detalle','id');
    }
}
