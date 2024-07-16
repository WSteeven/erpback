<?php

namespace App\Models\RecursosHumanos\NominaPrestamos;

use App\Models\Empleado;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class EgresoRolPago extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;
    protected $table = 'egreso_rol_pago';
    protected $fillable = [
        'descuento_id',
        'id_rol_pago',
        'empleado_id',
        'monto'
    ];

    private static $whiteListFilter = [
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
     * This PHP function creates an expense for a payment role and returns a egreso.
     *
     * @param rol_pago Rol de pago a la que pertenece egreso
     * @param monto valor monetario del egreso
     * @param entidad The variable "entidad" is an instance of a model class that has a relationship with
     * the "egreso_rol_pago" table. The "->egreso_rol_pago()" method is used to create a new record in the
     * "egreso_rol_pago" table associated with the "entidad
     *
     * @return the newly created "egreso_rol_pago" egreso object.
     */
    public static function crearEgresoRol(RolPago $rol_pago, $monto, $entidad)
    {
        $egreso = $entidad->egreso_rol_pago()->create([
            'id_rol_pago' => $rol_pago->id,
            'empleado_id' => $rol_pago->empleado_id,
            'monto' => $monto,
        ]);
        return $egreso;
    }
    /**
     * La función "editarEgresoRol" actualiza los campos "id_role_pago" y "monto" de la tabla
     * "rol_pago" en la base de datos.
     *
     * @param rol_pago El parámetro "rol_pago" es el ID del pago del rol que deseas editar. Se utiliza
     * para identificar el pago del rol específico en la base de datos.
     * @param monto El parámetro "monto" representa el monto actualizado para la entidad
     * "egreso_rol_pago".
     * @param entidad El parámetro "entidad" es una instancia de un modelo u objeto que representa una
     * entidad en su aplicación. Se utiliza para acceder a la relación entre la entidad y la tabla
     * "egreso_rol_pago".
     *
     * @return el objeto "egreso" actualizado.
     */
    public static function editarEgresoRol($rol_pago, $monto, $egreso_id, $entidad)
    {
        $egreso = $entidad->egreso_rol_pago()->where('id', $egreso_id)->first();
        $egreso->update([
            'id_rol_pago' => $rol_pago->id,
            'empleado_id' => $rol_pago->empleado_id,
            'monto' => $monto,
        ]);
        return $egreso;
    }

    public static function guardarEgresos($egreso, $rolPago)
    {
        try {
            DB::beginTransaction();
            $id_descuento = $egreso['id_descuento'];
            $tipo = null;
            $entidad = null;
            switch ($egreso['tipo']) {
                case 'DESCUENTO_GENERAL':
                    $tipo = 'App\Models\RecursosHumanos\NominaPrestamos\DescuentosGenerales';
                    $entidad = DescuentosGenerales::find($id_descuento);
                    break;
                case 'MULTA':
                    $tipo = 'App\Models\RecursosHumanos\NominaPrestamos\Multas';
                    $entidad = Multas::find($id_descuento);
                    break;
            }
            if (!$entidad) {
                throw new \Exception("No se encontró la entidad para el ID de descuento: $id_descuento");
            }
            if (isset($egreso['id'])) {
                EgresoRolPago::editarEgresoRol($rolPago, $egreso['monto'], $egreso['id'], $entidad);
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
