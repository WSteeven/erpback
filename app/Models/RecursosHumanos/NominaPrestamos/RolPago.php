<?php

namespace App\Models\RecursosHumanos\NominaPrestamos;

use App\Models\Empleado;
use Carbon\Carbon;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
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
        'salario',
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
        'rol_firmado',
        'medio_tiempo',
        'fondos_reserva',

    ];
    private static $whiteListFilter = [
        'id',
        'mes',
        'empleado',
        'bonificacion',
        'bono_recurente',
        'dias',
        'sueldo',
        'salario',
        'total_ingreso',
        'total_egreso',
        'total',
        'estado',
        'rol_pago_id',
        'rol_firmado',
        'fondos_reserva',
        'medio_tiempo',


    ];
    protected $casts = ['medio_tiempo' => 'boolean'];

    public function empleado_info()
    {
        return $this->belongsTo(Empleado::class, 'empleado_id', 'id')->with('cargo','user');
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
            $row['departamento'] = $rol_pago->empleado_info->departamento != null ? $rol_pago->empleado_info->departamento->nombre : '';
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
            $row['rol_firmado'] = $rol_pago->rol_firmado ? json_decode($rol_pago->rol_firmado)->ruta : null;
            $results[$id] = $row;
            $id++;
        }
        usort($results, __CLASS__ . "::ordenar_por_nombres_apellidos");

        return $results;
    }
    public static function empaquetarCash($rol_pagos)
    {
        $results = [];
        $id = 0;
        $row = [];

        foreach ($rol_pagos as $rol_pago) {
            $cuenta_bancarea_num = intval($rol_pago->empleado_info->num_cuenta_bancaria);
            if ($cuenta_bancarea_num > 0) {
            $referencia = $rol_pago->rolPagoMes->es_quincena?'PAGO ROL PRIMERA QUINCENA MES ':'PAGO ROL FIN DE MES ';
            $row['item'] = $id + 1;
            $row['empleado_info'] =  $rol_pago->empleado_info->apellidos . ' ' . $rol_pago->empleado_info->nombres;
            $row['departamento'] = $rol_pago->empleado_info->departamento->nombre;
            $row['numero_cuenta_bancareo'] =  $rol_pago->empleado_info->num_cuenta_bancaria;
            $row['email'] =  $rol_pago->empleado_info->user->email;
            $row['tipo_pago'] = 'PA';
            $row['numero_cuenta_empresa'] = '02653010903';
            $row['moneda'] = 'USD';
            $row['forma_pago'] = 'CTA';
            $row['codigo_banco'] = '0036';
            $row['tipo_cuenta'] = 'AHO';
            $row['tipo_documento_empleado'] = 'C';
            $row['referencia'] = strtoupper($referencia . ucfirst(Carbon::createFromFormat('m-Y', $rol_pago->mes)->locale('es')->translatedFormat('F')));
            $row['identificacion'] =  $rol_pago->empleado_info->identificacion;
            $row['total'] =  number_format($rol_pago->total, 2, ',', '.') ;
            $results[$id] = $row;

            $id++;
            }
        }
        usort($results, __CLASS__ . "::ordenar_por_nombres_apellidos");

        return $results;
    }
    // Relacion uno a muchos (inversa)
    public function rolPagoMes()
    {
        return $this->hasOne(RolPagoMes::class, 'id', 'rol_pago_id');
    }
    private static function  ordenar_por_nombres_apellidos($a, $b)
    {
        $nameA = $a['empleado_info'] . ' ' . $a['empleado_info'];
        $nameB = $b['empleado_info'] . ' ' . $b['empleado_info'];
        return strcmp($nameA, $nameB);
    }
}
