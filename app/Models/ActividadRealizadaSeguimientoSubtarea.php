<?php

namespace App\Models;

use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;

/**
 * App\Models\ActividadRealizadaSeguimientoSubtarea
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
 * @method static \Illuminate\Database\Eloquent\Builder|ActividadRealizadaSeguimientoSubtarea acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ActividadRealizadaSeguimientoSubtarea filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ActividadRealizadaSeguimientoSubtarea ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ActividadRealizadaSeguimientoSubtarea newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ActividadRealizadaSeguimientoSubtarea newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ActividadRealizadaSeguimientoSubtarea query()
 * @method static \Illuminate\Database\Eloquent\Builder|ActividadRealizadaSeguimientoSubtarea setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ActividadRealizadaSeguimientoSubtarea setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ActividadRealizadaSeguimientoSubtarea setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|ActividadRealizadaSeguimientoSubtarea whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActividadRealizadaSeguimientoSubtarea whereFechaHora($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActividadRealizadaSeguimientoSubtarea whereFotografia($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActividadRealizadaSeguimientoSubtarea whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActividadRealizadaSeguimientoSubtarea whereSeguimientoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActividadRealizadaSeguimientoSubtarea whereSubtareaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActividadRealizadaSeguimientoSubtarea whereTrabajoRealizado($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActividadRealizadaSeguimientoSubtarea whereUpdatedAt($value)
 * @mixin \Eloquent
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

    private static $whiteListFilter = [
        '*',
    ];
}
