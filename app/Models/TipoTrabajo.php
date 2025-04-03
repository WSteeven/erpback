<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use App\Traits\UppercaseValuesTrait;

/**
 * App\Models\TipoTrabajo
 *
 * @property int $id
 * @property string $descripcion
 * @property int $cliente_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property bool $activo
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Cliente|null $cliente
 * @method static \Illuminate\Database\Eloquent\Builder|TipoTrabajo acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoTrabajo filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoTrabajo ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoTrabajo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TipoTrabajo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TipoTrabajo query()
 * @method static \Illuminate\Database\Eloquent\Builder|TipoTrabajo setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoTrabajo setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoTrabajo setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoTrabajo whereActivo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoTrabajo whereClienteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoTrabajo whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoTrabajo whereDescripcion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoTrabajo whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoTrabajo whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class TipoTrabajo extends Model implements Auditable
{
    use HasFactory, AuditableModel, Filterable, UppercaseValuesTrait;

    protected $table = "tipos_trabajos";
    protected $fillable = [
        'descripcion',
        'activo',
        'cliente_id',
    ];

    private static $whiteListFilter = [
        '*',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    // Relacion uno a muchos (inversa)
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }
}
