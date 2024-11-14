<?php

namespace App\Models\RecursosHumanos\NominaPrestamos;

use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

/**
 * App\Models\RecursosHumanos\NominaPrestamos\IngresoRolPago
 *
 * @property int $id
 * @property int $id_rol_pago
 * @property int $concepto
 * @property string $monto
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\RecursosHumanos\NominaPrestamos\ConceptoIngreso|null $concepto_ingreso_info
 * @method static \Illuminate\Database\Eloquent\Builder|IngresoRolPago acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|IngresoRolPago filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|IngresoRolPago ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|IngresoRolPago newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|IngresoRolPago newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|IngresoRolPago query()
 * @method static \Illuminate\Database\Eloquent\Builder|IngresoRolPago setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|IngresoRolPago setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|IngresoRolPago setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|IngresoRolPago whereConcepto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IngresoRolPago whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IngresoRolPago whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IngresoRolPago whereIdRolPago($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IngresoRolPago whereMonto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IngresoRolPago whereUpdatedAt($value)
 * @mixin \Eloquent
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

    public static function guardarIngresos($ingreso, $rolPago)
    {
        DB::beginTransaction();
        try {
            $ingresoData = [
                'id_rol_pago' => $rolPago->id,
                'monto' => $ingreso['monto'],
                'concepto' => $ingreso['concepto'],
            ];

            IngresoRolPago::updateOrInsert(
                ['id_rol_pago' => $ingresoData['id_rol_pago'], 'concepto' => $ingresoData['concepto']],
                $ingresoData
            );

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

}
