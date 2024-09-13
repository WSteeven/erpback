<?php

namespace App\Models\RecursosHumanos\NominaPrestamos;

use App\Models\Empleado;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

/**
 * App\Models\RecursosHumanos\NominaPrestamos\PrestamoQuirografario
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
 * @property-read Empleado|null $empleado
 * @method static \Illuminate\Database\Eloquent\Builder|PrestamoQuirografario acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|PrestamoQuirografario filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|PrestamoQuirografario ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|PrestamoQuirografario newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PrestamoQuirografario newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PrestamoQuirografario query()
 * @method static \Illuminate\Database\Eloquent\Builder|PrestamoQuirografario setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|PrestamoQuirografario setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|PrestamoQuirografario setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|PrestamoQuirografario whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrestamoQuirografario whereEmpleadoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrestamoQuirografario whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrestamoQuirografario whereMes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrestamoQuirografario whereNut($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrestamoQuirografario whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrestamoQuirografario whereValor($value)
 * @mixin \Eloquent
 */
class PrestamoQuirografario extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;
    protected $table = 'prestamo_quirorafario';
    protected $fillable = [
        'mes','empleado_id','nut','valor'
    ];
    protected $casts = [
        'valor' => 'decimal:2'
    ];
    private static array $whiteListFilter = [
        'id',
        'empleado',
        'mes',
        'nut',
        'valor',
    ];
    public function empleado(){
        return $this->hasOne(Empleado::class,'id', 'empleado_id');
    }
}
