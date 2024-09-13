<?php

namespace App\Models\RecursosHumanos\NominaPrestamos;

use App\Models\Empleado;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;


/**
 * App\Models\RecursosHumanos\NominaPrestamos\Familiares
 *
 * @property int $id
 * @property string $identificacion
 * @property string $parentezco
 * @property string $nombres
 * @property string $apellidos
 * @property int $empleado_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read Empleado|null $empleado_info
 * @method static \Illuminate\Database\Eloquent\Builder|Familiares acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Familiares filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Familiares ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Familiares newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Familiares newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Familiares query()
 * @method static \Illuminate\Database\Eloquent\Builder|Familiares setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Familiares setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Familiares setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|Familiares whereApellidos($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Familiares whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Familiares whereEmpleadoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Familiares whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Familiares whereIdentificacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Familiares whereNombres($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Familiares whereParentezco($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Familiares whereUpdatedAt($value)
 * @mixin \Eloquent
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

    private static $whiteListFilter = [
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
