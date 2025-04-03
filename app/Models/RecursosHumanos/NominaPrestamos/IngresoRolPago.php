<?php

namespace App\Models\RecursosHumanos\NominaPrestamos;

use Eloquent;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Log;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Models\Audit;
use Throwable;

/**
 * App\Models\RecursosHumanos\NominaPrestamos\IngresoRolPago
 *
 * @property int $id
 * @property int $id_rol_pago
 * @property int $concepto
 * @property string $monto
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @property-read ConceptoIngreso|null $concepto_ingreso_info
 * @method static Builder|IngresoRolPago acceptRequest(?array $request = null)
 * @method static Builder|IngresoRolPago filter(?array $request = null)
 * @method static Builder|IngresoRolPago ignoreRequest(?array $request = null)
 * @method static Builder|IngresoRolPago newModelQuery()
 * @method static Builder|IngresoRolPago newQuery()
 * @method static Builder|IngresoRolPago query()
 * @method static Builder|IngresoRolPago setBlackListDetection(?array $black_list_detections = null)
 * @method static Builder|IngresoRolPago setCustomDetection(?array $object_custom_detect = null)
 * @method static Builder|IngresoRolPago setLoadInjectedDetection($load_default_detection)
 * @method static Builder|IngresoRolPago whereConcepto($value)
 * @method static Builder|IngresoRolPago whereCreatedAt($value)
 * @method static Builder|IngresoRolPago whereId($value)
 * @method static Builder|IngresoRolPago whereIdRolPago($value)
 * @method static Builder|IngresoRolPago whereMonto($value)
 * @method static Builder|IngresoRolPago whereUpdatedAt($value)
 * @mixin Eloquent
 */
class IngresoRolPago extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;
    protected $table = 'ingreso_rol_pago';
    protected $fillable = [
        'concepto',
        'id_rol_pago',
        'monto'
    ];

    private static array $whiteListFilter = [
        'id',
        'rol_pago',
        'monto'
    ];
    public function concepto_ingreso_info()
    {
        return $this->hasOne(ConceptoIngreso::class,'id', 'concepto');
    }

    /**
     * @throws Throwable
     */
//    public static function guardarIngresos($ingreso, $rolPago)
//    {
//        DB::beginTransaction();
//        try {
//            $ingresoData = [
//                'id_rol_pago' => $rolPago->id,
//                'monto' => $ingreso['monto'],
//                'concepto' => $ingreso['concepto'],
//            ];
//
//            IngresoRolPago::updateOrInsert(
//                ['id_rol_pago' => $ingresoData['id_rol_pago'], 'concepto' => $ingresoData['concepto']],
//                $ingresoData
//            );
//
//            DB::commit();
//        } catch (Exception $e) {
//            DB::rollback();
//            Log::channel('testing')->error('Log', ['error guardarIngresos', $e->getMessage(), $e->getLine()]);
//            throw $e;
//        }
//    }

}
