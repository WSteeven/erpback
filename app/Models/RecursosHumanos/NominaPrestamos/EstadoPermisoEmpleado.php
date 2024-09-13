<?php

namespace App\Models\RecursosHumanos\NominaPrestamos;

use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

/**
 * App\Models\RecursosHumanos\NominaPrestamos\EstadoPermisoEmpleado
 *
 * @property int $id
 * @property string $nombre
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @method static \Illuminate\Database\Eloquent\Builder|EstadoPermisoEmpleado acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|EstadoPermisoEmpleado filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|EstadoPermisoEmpleado ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|EstadoPermisoEmpleado newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EstadoPermisoEmpleado newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EstadoPermisoEmpleado query()
 * @method static \Illuminate\Database\Eloquent\Builder|EstadoPermisoEmpleado setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|EstadoPermisoEmpleado setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|EstadoPermisoEmpleado setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|EstadoPermisoEmpleado whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EstadoPermisoEmpleado whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EstadoPermisoEmpleado whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EstadoPermisoEmpleado whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class EstadoPermisoEmpleado extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;
    protected $table = 'estado_permiso_empleados';
    protected $fillable = [
        'nombre'
    ];

    private static $whiteListFilter = [
        'id',
        'nombre',
    ];
}
