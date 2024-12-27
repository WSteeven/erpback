<?php

namespace App\Models\RecursosHumanos\NominaPrestamos;

use App\Models\Empleado;
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
 * App\Models\RecursosHumanos\NominaPrestamos\Familiares
 *
 * @property int $id
 * @property string $identificacion
 * @property string $parentezco
 * @property string $nombres
 * @property string $apellidos
 * @property int $empleado_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @property-read Empleado|null $empleado_info
 * @method static Builder|Familiares acceptRequest(?array $request = null)
 * @method static Builder|Familiares filter(?array $request = null)
 * @method static Builder|Familiares ignoreRequest(?array $request = null)
 * @method static Builder|Familiares newModelQuery()
 * @method static Builder|Familiares newQuery()
 * @method static Builder|Familiares query()
 * @method static Builder|Familiares setBlackListDetection(?array $black_list_detections = null)
 * @method static Builder|Familiares setCustomDetection(?array $object_custom_detect = null)
 * @method static Builder|Familiares setLoadInjectedDetection($load_default_detection)
 * @method static Builder|Familiares whereApellidos($value)
 * @method static Builder|Familiares whereCreatedAt($value)
 * @method static Builder|Familiares whereEmpleadoId($value)
 * @method static Builder|Familiares whereId($value)
 * @method static Builder|Familiares whereIdentificacion($value)
 * @method static Builder|Familiares whereNombres($value)
 * @method static Builder|Familiares whereParentezco($value)
 * @method static Builder|Familiares whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Familiares extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;
    const  CONYUGE= 'CÓNYUGE';
    const HIJO = 'HIJO';
    const HIJA = 'HIJA';
    protected $table = 'familiares';
    protected $fillable = [
        'identificacion',
        'parentezco',
        'nombres',
        'apellidos',
        'empleado_id',

    ];

    private static array $whiteListFilter = [
        'id',
        'identificacion',
        'parentezco',
        'nombres',
        'apellidos',
        'empleado'
    ];
    public function empleado_info()
    {
        return $this->belongsTo(Empleado::class, 'empleado_id', 'id')->with('departamento','jefe');
    }
}
