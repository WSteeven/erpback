<?php

namespace App\Models\RecursosHumanos\NominaPrestamos;

use App\Models\Empleado;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

/**
 * App\Models\RecursosHumanos\NominaPrestamos\PrestamoHipotecario
 *
 * @property int $id
 * @property string $mes
 * @property int $empleado_id
 * @property string $nut
 * @property string $valor
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read Empleado|null $empleado_info
 * @method static \Illuminate\Database\Eloquent\Builder|PrestamoHipotecario acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|PrestamoHipotecario filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|PrestamoHipotecario ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|PrestamoHipotecario newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PrestamoHipotecario newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PrestamoHipotecario query()
 * @method static \Illuminate\Database\Eloquent\Builder|PrestamoHipotecario setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|PrestamoHipotecario setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|PrestamoHipotecario setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|PrestamoHipotecario whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrestamoHipotecario whereEmpleadoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrestamoHipotecario whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrestamoHipotecario whereMes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrestamoHipotecario whereNut($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrestamoHipotecario whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrestamoHipotecario whereValor($value)
 * @mixin \Eloquent
 */
class PrestamoHipotecario extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;
    protected $table = 'prestamo_hipotecario';
    protected $fillable = [
        'mes','empleado_id','nut','valor'
    ];
    protected $casts = [
        'valor' => 'decimal:2'
    ];
    private static $whiteListFilter = [
        'id',
        'empleado',
        'mes',
        'nut',
        'valor',
    ];
    public function empleado_info(){
        return $this->hasOne(Empleado::class,'id', 'empleado_id');
    }
}
