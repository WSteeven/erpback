<?php

namespace App\Models;

use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

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
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Model|\Eloquent $actividable
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @method static \Illuminate\Database\Eloquent\Builder|ActividadRealizada acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ActividadRealizada filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ActividadRealizada ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ActividadRealizada newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ActividadRealizada newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ActividadRealizada query()
 * @method static \Illuminate\Database\Eloquent\Builder|ActividadRealizada setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ActividadRealizada setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ActividadRealizada setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|ActividadRealizada whereActividableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActividadRealizada whereActividableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActividadRealizada whereActividadRealizada($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActividadRealizada whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActividadRealizada whereEmpleadoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActividadRealizada whereFechaHora($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActividadRealizada whereFotografia($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActividadRealizada whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActividadRealizada whereObservacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActividadRealizada whereUpdatedAt($value)
 * @mixin \Eloquent
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
        'actividable_type'
    ];


    private static $whiteListFilter = ['*'];

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
