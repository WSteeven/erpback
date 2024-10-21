<?php

namespace App\Models\RecursosHumanos\Alimentacion;

use App\Models\Empleado;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

/**
 * App\Models\RecursosHumanos\Alimentacion\AsignarAlimentacion
 *
 * @property int $id
 * @property int $empleado_id
 * @property string $valor_minimo
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read Empleado|null $empleado
 * @method static \Illuminate\Database\Eloquent\Builder|AsignarAlimentacion acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|AsignarAlimentacion filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|AsignarAlimentacion ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|AsignarAlimentacion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AsignarAlimentacion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AsignarAlimentacion query()
 * @method static \Illuminate\Database\Eloquent\Builder|AsignarAlimentacion setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|AsignarAlimentacion setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|AsignarAlimentacion setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|AsignarAlimentacion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AsignarAlimentacion whereEmpleadoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AsignarAlimentacion whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AsignarAlimentacion whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AsignarAlimentacion whereValorMinimo($value)
 * @mixin \Eloquent
 */
class AsignarAlimentacion extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;
    protected $table = 'rrhh_asignar_alimentacion';
    protected $fillable = [
        'empleado_id',
        'valor_minimo'
    ];

    private static $whiteListFilter = [
        'empleado_id',
        'empleado',
        'valor_minimo'
    ];
    public function empleado(){
        return $this->hasOne(Empleado::class,'id','empleado_id');
    }

}
