<?php

namespace App\Models\RecursosHumanos\NominaPrestamos;

use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
        'monto'
    ];

    private static $whiteListFilter = [
        'id',
        'descuento',
        'rol_pago',
        'monto'
    ];
    //Relación polimorfica
    public function descuento()
    {
        return $this->morphTo();
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
    public static function crearEgresoRol($rol_pago, $monto, $entidad)
    {
        $egreso = $entidad->egreso_rol_pago()->create([
            'id_rol_pago' => $rol_pago,
            'monto' => $monto,
        ]);
        return $egreso;
    }
}
