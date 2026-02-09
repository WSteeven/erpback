<?php

namespace App\Models;

use App\Models\Tareas\MaterialDefecto;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use App\Traits\UppercaseValuesTrait;
use OwenIt\Auditing\Models\Audit;

/**
 * App\Models\TipoTrabajo
 *
 * @property int $id
 * @property string $descripcion
 * @property int $cliente_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property bool $activo
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @property-read Cliente|null $cliente
 * @method static Builder|TipoTrabajo acceptRequest(?array $request = null)
 * @method static Builder|TipoTrabajo filter(?array $request = null)
 * @method static Builder|TipoTrabajo ignoreRequest(?array $request = null)
 * @method static Builder|TipoTrabajo newModelQuery()
 * @method static Builder|TipoTrabajo newQuery()
 * @method static Builder|TipoTrabajo query()
 * @method static Builder|TipoTrabajo setBlackListDetection(?array $black_list_detections = null)
 * @method static Builder|TipoTrabajo setCustomDetection(?array $object_custom_detect = null)
 * @method static Builder|TipoTrabajo setLoadInjectedDetection($load_default_detection)
 * @method static Builder|TipoTrabajo whereActivo($value)
 * @method static Builder|TipoTrabajo whereClienteId($value)
 * @method static Builder|TipoTrabajo whereCreatedAt($value)
 * @method static Builder|TipoTrabajo whereDescripcion($value)
 * @method static Builder|TipoTrabajo whereId($value)
 * @method static Builder|TipoTrabajo whereUpdatedAt($value)
 * @mixin Eloquent
 */
class TipoTrabajo extends Model implements Auditable
{
    use HasFactory, AuditableModel, Filterable, UppercaseValuesTrait;

    protected $table = "tipos_trabajos";
    protected $fillable = [
        'descripcion',
        'activo',
        'cliente_id',
        'url_plantilla',
    ];

    private static array $whiteListFilter = [
        '*',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }
}
