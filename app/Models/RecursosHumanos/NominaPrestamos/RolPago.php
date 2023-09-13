<?php

namespace App\Models\RecursosHumanos\NominaPrestamos;

use App\Models\Empleado;
use Carbon\Carbon;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class RolPago extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;
    protected $table = 'rol_pago';
    const CREADO = 'CREADO';
    const EJECUTANDO = 'EJECUTANDO';
    const CANCELADO = 'CANCELADO';
    const REALIZADO = 'REALIZADO';
    const FINALIZADO = 'FINALIZADO';
    protected $fillable = [
        'empleado_id',
        'mes',
        'dias',
        'sueldo',
        'anticipo',
        'bonificacion',
        'bono_recurente',
        'decimo_tercero',
        'decimo_cuarto',
        'prestamo_quirorafario',
        'prestamo_hipotecario',
        'prestamo_empresarial',
        'fondos_reserva',
        'total_ingreso',
        'iess',
        'total_egreso',
        'total',
        'estado',
        'rol_pago_id',
        'rol_firmado'

    ];
    private static $whiteListFilter = [
        'id',
        'mes',
        'empleado',
        'bonificacion',
        'bono_recurente',
        'dias',
        'sueldo',
        'total_ingreso',
        'total_egreso',

        'total',
        'estado',
        'rol_pago_id',
        'rol_firmado',
        'fondos_reserva',

    ];

    public function empleado_info()
    {
        return $this->belongsTo(Empleado::class, 'empleado_id', 'id')->with('cargo');
    }

    public function egreso_rol_pago()
    {
        return $this->hasMany(EgresoRolPago::class, 'id_rol_pago', 'id')->with('descuento');
    }
    public function ingreso_rol_pago()
    {
        return $this->hasMany(IngresoRolPago::class, 'id_rol_pago', 'id')->with('concepto_ingreso_info');
    }
    public static function empaquetarListado($rol_pagos)
    {
        $results = [];
        $id = 0;
        $row = [];

        foreach ($rol_pagos as $rol_pago) {

            $row['item'] = $id + 1;
            $row['id'] =  $rol_pago->id;
            $row['empleado_info'] =  $rol_pago->empleado_info->apellidos . ' ' . $rol_pago->empleado_info->nombres;
            $row['cedula'] =  $rol_pago->empleado_info->identificacion;
            $row['salario'] =  $rol_pago->empleado_info->salario;
            $row['mes'] =  ucfirst(Carbon::createFromFormat('m-Y', $rol_pago->mes)->locale('es')->translatedFormat('F \d\e Y'));
            $row['identificacion_empleado'] =  $rol_pago->empleado_info->identificacion;
            $row['cargo'] = $rol_pago->empleado_info->cargo != null ? $rol_pago->empleado_info->cargo->nombre : '';
            $row['ciudad'] = $rol_pago->empleado_info->canton != null ? $rol_pago->empleado_info->canton->canton : '';
            $row['dias_laborados'] = $rol_pago->dias;
            $row['sueldo'] = $rol_pago->sueldo;
            $row['decimo_tercero'] = $rol_pago->decimo_tercero;
            $row['decimo_cuarto'] = $rol_pago->decimo_cuarto;
            $row['fondos_reserva'] = $rol_pago->fondos_reserva;
            $row['bonificacion'] = $rol_pago->bonificacion;
            $row['bono_recurente'] = $rol_pago->bono_recurente;
            $row['iess'] = $rol_pago->iess;
            $row['anticipo'] = $rol_pago->anticipo;
            $row['prestamo_quirorafario'] = $rol_pago->prestamo_quirorafario;
            $row['prestamo_hipotecario'] = $rol_pago->prestamo_hipotecario;
            $row['extension_conyugal'] = $rol_pago->extension_conyugal;
            $row['prestamo_empresarial'] = $rol_pago->prestamo_empresarial;
            $row['total_ingreso'] = $rol_pago->total_ingreso;
            $row['total_egreso'] = $rol_pago->total_egreso;
            $row['total'] = $rol_pago->total;
            $row['supa'] = $rol_pago->empleado_info->supa;
            $row['ingresos'] = $rol_pago->ingreso_rol_pago;
            $row['egresos'] = $rol_pago->egreso_rol_pago;
            $row['egresos_cantidad_columna'] = count($rol_pago->egreso_rol_pago);
            $row['ingresos_cantidad_columna'] = count($rol_pago->ingreso_rol_pago);

            $results[$id] = $row;

            $id++;
        }
        return $results;
    }
     // Relacion uno a muchos (inversa)
     public function rolPagoMes()
     {
         return $this->hasOne(RolPagoMes::class, 'id','rol_pago_id');
     }
}
