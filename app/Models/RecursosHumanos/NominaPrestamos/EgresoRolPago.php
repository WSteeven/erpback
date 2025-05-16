<?php

namespace App\Models\RecursosHumanos\NominaPrestamos;

use App\Models\Empleado;
use App\Traits\UppercaseValuesTrait;
use Eloquent;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Models\Audit;
use Throwable;

/**
 * App\Models\RecursosHumanos\NominaPrestamos\EgresoRolPago
 *
 * @property int $id
 * @property int $id_rol_pago
 * @property int $descuento_id
 * @property string $descuento_type
 * @property string $monto
 * @property int|null $empleado_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @property-read Model|Eloquent $descuento
 * @property-read Empleado|null $empleado
 * @method static Builder|EgresoRolPago acceptRequest(?array $request = null)
 * @method static Builder|EgresoRolPago filter(?array $request = null)
 * @method static Builder|EgresoRolPago ignoreRequest(?array $request = null)
 * @method static Builder|EgresoRolPago newModelQuery()
 * @method static Builder|EgresoRolPago newQuery()
 * @method static Builder|EgresoRolPago query()
 * @method static Builder|EgresoRolPago setBlackListDetection(?array $black_list_detections = null)
 * @method static Builder|EgresoRolPago setCustomDetection(?array $object_custom_detect = null)
 * @method static Builder|EgresoRolPago setLoadInjectedDetection($load_default_detection)
 * @method static Builder|EgresoRolPago whereCreatedAt($value)
 * @method static Builder|EgresoRolPago whereDescuentoId($value)
 * @method static Builder|EgresoRolPago whereDescuentoType($value)
 * @method static Builder|EgresoRolPago whereEmpleadoId($value)
 * @method static Builder|EgresoRolPago whereId($value)
 * @method static Builder|EgresoRolPago whereIdRolPago($value)
 * @method static Builder|EgresoRolPago whereMonto($value)
 * @method static Builder|EgresoRolPago whereUpdatedAt($value)
 * @mixin Eloquent
 */
class EgresoRolPago extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use UppercaseValuesTrait;
    use Filterable;
    protected $table = 'egreso_rol_pago';
    protected $fillable = [
        'descuento_id',
        'id_rol_pago',
        'empleado_id',
        'monto'
    ];

    protected $casts=[
        'monto'=>'float'
    ];

    private static array $whiteListFilter = [
        'id',
        'descuento',
        'rol_pago',
        'empleado_id',
        'empleado',
        'monto'
    ];
    //Relación polimorfica
    public function descuento()
    {
        return $this->morphTo();
    }
    public function empleado()
    {
        return $this->hasOne(Empleado::class, 'id', 'empleado_id');
    }

    /**
     * Esta función polimorfica crea un registro de egreso en determinado rol
     * asociado a determinada entidad originadora.
     *
     * @param RolPago $rol_pago
     * @param $monto
     * @param $entidad
     * @return Collection
     */
    public static function crearEgresoRol(RolPago $rol_pago, $monto, $entidad)
    {
        if($entidad->egreso_rol_pago()->where('id_rol_pago', $rol_pago->id)->exists()) {
            Log::channel('testing')->info('Log', ['Ya existe el egreso rol para esta entidad', $entidad->egreso_rol_pago()->where('id_rol_pago', $rol_pago->id)->get()]);
            return null;
        }
        return $entidad->egreso_rol_pago()->create([
            'id_rol_pago' => $rol_pago->id,
            'empleado_id' => $rol_pago->empleado_id,
            'monto' => $monto,
        ]);
    }
    /**
     * La función "editarEgresoRol" actualiza los campos "id_role_pago" y "monto" de la tabla
     * "rol_pago" en la base de datos.
     *
     */
//    public static function editarEgresoRol(RolPago $rol_pago,float $monto,int $egreso_id, Model $entidad)
//    {
//        $egreso = $entidad->egreso_rol_pago()->where('id', $egreso_id)->first();
//        $egreso->update([
//            'id_rol_pago' => $rol_pago->id,
//            'empleado_id' => $rol_pago->empleado_id,
//            'monto' => $monto,
//        ]);
//        return $egreso;
//    }

    /**
     * Esta función actualiza cualquier cambio realizado en un egreso
     * @throws Throwable
     */
//    public static function guardarEgresos(array $egreso,RolPago $rolPago){
//        $entidad = "";//new EgresoRolPago();
//        self::editarEgresoRol($rolPago, $egreso['monto'],$egreso['id'], $entidad);
//    }
//    public static function guardarEgresosOld(array $egreso,RolPago $rolPago)
//    {
//        try {
//            DB::beginTransaction();
//            $id_descuento = $egreso['id_descuento'];
//            $tipo = null;
//            $entidad = null;
//            switch ($egreso['tipo']) {
//                case 'DESCUENTO_GENERAL':
//                    $tipo = 'App\Models\RecursosHumanos\NominaPrestamos\DescuentosGenerales';
//                    $entidad = DescuentosGenerales::find($id_descuento);
//                    break;
//                case 'MULTA':
//                    $tipo = 'App\Models\RecursosHumanos\NominaPrestamos\Multas';
//                    $entidad = Multas::find($id_descuento);
//                    break;
//            }
//            if (!$entidad) {
//                throw new Exception("No se encontró la entidad para el ID de descuento: $id_descuento");
//            }
//            if (isset($egreso['id'])) {
//                EgresoRolPago::editarEgresoRol($rolPago, $egreso['monto'], $egreso['id'], $entidad);
//            }
//            DB::commit();
//        } catch (Exception $e) {
//            DB::rollBack();
//            throw $e;
//        }
//    }
}
