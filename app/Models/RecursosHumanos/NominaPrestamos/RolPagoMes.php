<?php

namespace App\Models\RecursosHumanos\NominaPrestamos;

use App\Models\Empleado;
use Carbon\Carbon;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\RecursosHumanos\NominaPrestamos\RolPagoMes
 *
 * @property int $id
 * @property string $nombre
 * @property string $mes
 * @property bool $finalizado
 * @property bool $es_quincena
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\RecursosHumanos\NominaPrestamos\RolPago> $rolPago
 * @property-read int|null $rol_pago_count
 * @method static \Illuminate\Database\Eloquent\Builder|RolPagoMes acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|RolPagoMes filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|RolPagoMes ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|RolPagoMes newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RolPagoMes newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RolPagoMes query()
 * @method static \Illuminate\Database\Eloquent\Builder|RolPagoMes setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|RolPagoMes setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|RolPagoMes setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|RolPagoMes whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RolPagoMes whereEsQuincena($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RolPagoMes whereFinalizado($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RolPagoMes whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RolPagoMes whereMes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RolPagoMes whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RolPagoMes whereUpdatedAt($value)
 * @mixin \Eloquent
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
    private static $whiteListFilter = [
        'id',
        'mes',
        'nombre',
        'finalizado',
        'es_quincena'
    ];
    protected $casts = ['finalizado' => 'boolean','es_quincena'=>'boolean'];
    public function rolPago()
    {
        return $this->hasMany(RolPago::class,'rol_pago_id','id');
    }

}
