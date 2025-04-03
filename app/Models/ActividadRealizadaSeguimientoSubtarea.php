<?php

namespace App\Models;

use App\Traits\UppercaseValuesTrait;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use OwenIt\Auditing\Models\Audit;

/**
 * App\Models\ActividadRealizadaSeguimientoSubtarea
 *
 * @property int $id
 * @property string $fecha_hora
 * @property string $trabajo_realizado
 * @property string|null $fotografia
 * @property int|null $seguimiento_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int $subtarea_id
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @method static Builder|ActividadRealizadaSeguimientoSubtarea acceptRequest(?array $request = null)
 * @method static Builder|ActividadRealizadaSeguimientoSubtarea filter(?array $request = null)
 * @method static Builder|ActividadRealizadaSeguimientoSubtarea ignoreRequest(?array $request = null)
 * @method static Builder|ActividadRealizadaSeguimientoSubtarea newModelQuery()
 * @method static Builder|ActividadRealizadaSeguimientoSubtarea newQuery()
 * @method static Builder|ActividadRealizadaSeguimientoSubtarea query()
 * @method static Builder|ActividadRealizadaSeguimientoSubtarea setBlackListDetection(?array $black_list_detections = null)
 * @method static Builder|ActividadRealizadaSeguimientoSubtarea setCustomDetection(?array $object_custom_detect = null)
 * @method static Builder|ActividadRealizadaSeguimientoSubtarea setLoadInjectedDetection($load_default_detection)
 * @method static Builder|ActividadRealizadaSeguimientoSubtarea whereCreatedAt($value)
 * @method static Builder|ActividadRealizadaSeguimientoSubtarea whereFechaHora($value)
 * @method static Builder|ActividadRealizadaSeguimientoSubtarea whereFotografia($value)
 * @method static Builder|ActividadRealizadaSeguimientoSubtarea whereId($value)
 * @method static Builder|ActividadRealizadaSeguimientoSubtarea whereSeguimientoId($value)
 * @method static Builder|ActividadRealizadaSeguimientoSubtarea whereSubtareaId($value)
 * @method static Builder|ActividadRealizadaSeguimientoSubtarea whereTrabajoRealizado($value)
 * @method static Builder|ActividadRealizadaSeguimientoSubtarea whereUpdatedAt($value)
 * @mixin Eloquent
 */
class ActividadRealizadaSeguimientoSubtarea extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, Filterable, AuditableModel;

    protected $table = 'trabajos_realizados';
    protected $fillable = ['fecha_hora', 'trabajo_realizado', 'fotografia', 'subtarea_id'];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
    ];

    private static array $whiteListFilter = [
        '*',
    ];
}
