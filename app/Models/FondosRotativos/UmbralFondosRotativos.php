<?php

namespace App\Models\FondosRotativos;

use App\Models\Empleado;
use App\Models\FondosRotativos\Saldo\SaldoGrupo;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

/**
 * App\Models\FondosRotativos\UmbralFondosRotativos
 *
 * @property int $id
 * @property int $empleado_id
 * @property string $valor_minimo
 * @property string $referencia
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read Empleado|null $empleado
 * @method static \Illuminate\Database\Eloquent\Builder|UmbralFondosRotativos acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|UmbralFondosRotativos filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|UmbralFondosRotativos ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|UmbralFondosRotativos newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UmbralFondosRotativos newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UmbralFondosRotativos query()
 * @method static \Illuminate\Database\Eloquent\Builder|UmbralFondosRotativos setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|UmbralFondosRotativos setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|UmbralFondosRotativos setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|UmbralFondosRotativos whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UmbralFondosRotativos whereEmpleadoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UmbralFondosRotativos whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UmbralFondosRotativos whereReferencia($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UmbralFondosRotativos whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UmbralFondosRotativos whereValorMinimo($value)
 * @mixin \Eloquent
 */
class UmbralFondosRotativos extends  Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;
    use UppercaseValuesTrait;
    protected $table = 'fr_umbral_fondos_rotativos';
    protected $primaryKey = 'id';
    protected $fillable = [
        'valor_minimo',
        'referencia',
        'empleado_id',
    ];
    private static $whiteListFilter = [
        'referencia',
        'empleado_id',
        'valor_minimo',
    ];
    public function empleado()
    {
        return $this->hasOne(Empleado::class, 'id', 'empleado_id');
    }

}
