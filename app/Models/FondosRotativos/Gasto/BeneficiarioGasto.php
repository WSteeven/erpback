<?php

namespace App\Models\FondosRotativos\Gasto;

use App\Models\Empleado;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

/**
 * App\Models\FondosRotativos\Gasto\BeneficiarioGasto
 *
 * @property int $id
 * @property int $gasto_id
 * @property int $empleado_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read Empleado|null $empleado
 * @property-read \App\Models\FondosRotativos\Gasto\Gasto|null $gasto
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\FondosRotativos\Gasto\Gasto> $gastos
 * @property-read int|null $gastos_count
 * @method static \Illuminate\Database\Eloquent\Builder|BeneficiarioGasto acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|BeneficiarioGasto filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|BeneficiarioGasto ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|BeneficiarioGasto newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BeneficiarioGasto newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BeneficiarioGasto query()
 * @method static \Illuminate\Database\Eloquent\Builder|BeneficiarioGasto setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|BeneficiarioGasto setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|BeneficiarioGasto setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|BeneficiarioGasto whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BeneficiarioGasto whereEmpleadoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BeneficiarioGasto whereGastoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BeneficiarioGasto whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BeneficiarioGasto whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class BeneficiarioGasto extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;
    use UppercaseValuesTrait;
    protected $table = 'beneficiario_gastos';
    protected $primaryKey = 'id';
    protected $fillable = [
        'gasto_id',
        'beneficiario',
        'empleado_id',
    ];
    private static $whiteListFilter = [
        'gasto',
        'gasto_id',
        'empleado_id',
        'beneficiario',
    ];
    public function gasto()
    {
        return $this->hasOne(Gasto::class, 'id','id_gasto');
    }
    public function empleado()
    {
        return $this->hasOne(Empleado::class, 'id', 'empleado_id');
    }
    public function gastos(){
        return $this->hasMany(Gasto::class,'id','id_gasto');
    }
}
