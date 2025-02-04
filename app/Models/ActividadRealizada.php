<?php

namespace App\Models;

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
 * App\Models\ActividadRealizada
 *
 * @property int $id
 * @property string $fecha_hora
 * @property string $actividad_realizada
 * @property string|null $observacion
 * @property string|null $fotografia
 * @property int|null $empleado_id
 * @property int $actividable_id
 * @property string $actividable_type
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Model|Eloquent $actividable
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @method static Builder|ActividadRealizada acceptRequest(?array $request = null)
 * @method static Builder|ActividadRealizada filter(?array $request = null)
 * @method static Builder|ActividadRealizada ignoreRequest(?array $request = null)
 * @method static Builder|ActividadRealizada newModelQuery()
 * @method static Builder|ActividadRealizada newQuery()
 * @method static Builder|ActividadRealizada query()
 * @method static Builder|ActividadRealizada setBlackListDetection(?array $black_list_detections = null)
 * @method static Builder|ActividadRealizada setCustomDetection(?array $object_custom_detect = null)
 * @method static Builder|ActividadRealizada setLoadInjectedDetection($load_default_detection)
 * @method static Builder|ActividadRealizada whereActividableId($value)
 * @method static Builder|ActividadRealizada whereActividableType($value)
 * @method static Builder|ActividadRealizada whereActividadRealizada($value)
 * @method static Builder|ActividadRealizada whereCreatedAt($value)
 * @method static Builder|ActividadRealizada whereEmpleadoId($value)
 * @method static Builder|ActividadRealizada whereFechaHora($value)
 * @method static Builder|ActividadRealizada whereFotografia($value)
 * @method static Builder|ActividadRealizada whereId($value)
 * @method static Builder|ActividadRealizada whereObservacion($value)
 * @method static Builder|ActividadRealizada whereUpdatedAt($value)
 * @mixin Eloquent
 */
class ActividadRealizada extends Model implements Auditable
{
    use HasFactory, Filterable, AuditableModel;
    protected $table = 'actividades_realizadas';
    protected $fillable = [
        'fecha_hora',
        'actividad_realizada',
        'observacion',
        'fotografia',
        'empleado_id',
        'actividable_id',
        'tarea_id',
        'kilometraje',
        'actividable_type'
    ];


    private static array $whiteListFilter = ['*'];

    /**
     * ______________________________________________________________________________________
     * RELACIONES CON OTRAS TABLAS
     * ______________________________________________________________________________________
     */

    // Relación polimorfica
    public function actividable() //actividad => activid + able
    {
        return $this->morphTo();
    }
}
