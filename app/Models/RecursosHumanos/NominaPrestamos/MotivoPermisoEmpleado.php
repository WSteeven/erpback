<?php

namespace App\Models\RecursosHumanos\NominaPrestamos;

use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;


/**
 * App\Models\RecursosHumanos\NominaPrestamos\MotivoPermisoEmpleado
 *
 * @property int $id
 * @property string $nombre
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @method static \Illuminate\Database\Eloquent\Builder|MotivoPermisoEmpleado acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|MotivoPermisoEmpleado filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|MotivoPermisoEmpleado ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|MotivoPermisoEmpleado newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MotivoPermisoEmpleado newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MotivoPermisoEmpleado query()
 * @method static \Illuminate\Database\Eloquent\Builder|MotivoPermisoEmpleado setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|MotivoPermisoEmpleado setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|MotivoPermisoEmpleado setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|MotivoPermisoEmpleado whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MotivoPermisoEmpleado whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MotivoPermisoEmpleado whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MotivoPermisoEmpleado whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class MotivoPermisoEmpleado extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;
    protected $table = 'motivo_permiso_empleados';
    protected $fillable = [
        'nombre'
    ];

    private static $whiteListFilter = [
        'id',
        'nombre',
    ];
}
