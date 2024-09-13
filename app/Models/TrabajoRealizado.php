<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use App\Traits\UppercaseValuesTrait;

/**
 * App\Models\TrabajoRealizado
 *
 * @property int $id
 * @property string $fecha_hora
 * @property string $trabajo_realizado
 * @property string|null $fotografia
 * @property int|null $seguimiento_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $subtarea_id
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\SeguimientoSubtarea|null $seguimiento
 * @method static \Illuminate\Database\Eloquent\Builder|TrabajoRealizado acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|TrabajoRealizado filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|TrabajoRealizado ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|TrabajoRealizado newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TrabajoRealizado newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TrabajoRealizado query()
 * @method static \Illuminate\Database\Eloquent\Builder|TrabajoRealizado setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|TrabajoRealizado setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|TrabajoRealizado setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|TrabajoRealizado whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrabajoRealizado whereFechaHora($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrabajoRealizado whereFotografia($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrabajoRealizado whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrabajoRealizado whereSeguimientoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrabajoRealizado whereSubtareaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrabajoRealizado whereTrabajoRealizado($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrabajoRealizado whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class TrabajoRealizado extends Model implements Auditable
{
    use HasFactory, AuditableModel, Filterable, UppercaseValuesTrait;

    protected $table = 'trabajos_realizados';
    protected $fillable = [
        'trabajo_realizado',
        'fotografia',
        'fecha_hora',
        'seguimiento_id',
    ];

    private static $whiteListFilter = [
        '*',
    ];

    // Relacion uno a muchos (inversa)
    public function seguimiento()
    {
        return $this->belongsTo(SeguimientoSubtarea::class);
    }
}
