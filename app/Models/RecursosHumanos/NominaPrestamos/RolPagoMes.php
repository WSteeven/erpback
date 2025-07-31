<?php

namespace App\Models\RecursosHumanos\NominaPrestamos;

use Eloquent;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Models\Audit;

/**
 * App\Models\RecursosHumanos\NominaPrestamos\RolPagoMes
 *
 * @property int $id
 * @property string $nombre
 * @property string $mes
 * @property bool $finalizado
 * @property bool $es_quincena
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @property-read Collection<int, RolPago> $rolPago
 * @property-read int|null $rol_pago_count
 * @method static Builder|RolPagoMes acceptRequest(?array $request = null)
 * @method static Builder|RolPagoMes filter(?array $request = null)
 * @method static Builder|RolPagoMes ignoreRequest(?array $request = null)
 * @method static Builder|RolPagoMes newModelQuery()
 * @method static Builder|RolPagoMes newQuery()
 * @method static Builder|RolPagoMes query()
 * @method static Builder|RolPagoMes setBlackListDetection(?array $black_list_detections = null)
 * @method static Builder|RolPagoMes setCustomDetection(?array $object_custom_detect = null)
 * @method static Builder|RolPagoMes setLoadInjectedDetection($load_default_detection)
 * @method static Builder|RolPagoMes whereCreatedAt($value)
 * @method static Builder|RolPagoMes whereEsQuincena($value)
 * @method static Builder|RolPagoMes whereFinalizado($value)
 * @method static Builder|RolPagoMes whereId($value)
 * @method static Builder|RolPagoMes whereMes($value)
 * @method static Builder|RolPagoMes whereNombre($value)
 * @method static Builder|RolPagoMes whereUpdatedAt($value)
 * @mixin Eloquent
 */
class RolPagoMes extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;
    protected $table = 'rol_pago_mes';
    protected $fillable = [
        'mes',
        'nombre',
        'finalizado',
        'es_quincena'
    ];
    private static array $whiteListFilter = [
        'id',
        'mes',
        'nombre',
        'finalizado',
        'es_quincena'
    ];
    protected $casts = ['finalizado' => 'boolean','es_quincena'=>'boolean'];
    public function rolesPagos()
    {
        return $this->hasMany(RolPago::class,'rol_pago_id','id');
    }

}
