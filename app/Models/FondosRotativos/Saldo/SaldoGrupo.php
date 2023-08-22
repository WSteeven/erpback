<?php

namespace App\Models\FondosRotativos\Saldo;

use App\Models\Empleado;
use App\Models\FondosRotativos\Saldo\TipoSaldo;
use App\Models\FondosRotativos\Viatico\EstadoViatico;
use App\Models\FondosRotativos\Viatico\TipoFondo;
use App\Models\User;
use Carbon\Carbon;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;
use Illuminate\Support\Facades\Auth;

class SaldoGrupo extends  Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;
    protected $table = 'saldo_grupo';
    protected $primaryKey = 'id';
    protected $fillable = [
        'fecha',
        'saldo_anterior',
        'saldo_depositado',
        'saldo_actual',
        'tipo_saldo',
        'fecha_inicio',
        'fecha_fin',
        'id_usuario',
    ];
    private static $whiteListFilter = [
        'fecha_inicio',
    ];
    public function usuario()
    {
        return $this->hasOne(Empleado::class, 'id', 'id_usuario')->with('user');
    }
    public static function empaquetarCombinado($arreglo, $empleado, $fecha, $saldo_anterior)
    {
        $results = [];
        $id = 0;
        $row = [];
        if (isset($arreglo)) {
            $results[0] = SaldoGrupo::saldoAnterior($id, $fecha, $saldo_anterior);
            $id += 1;
            $fecha_anterior = $fecha;
            foreach ($arreglo as $saldo) {
                if (isset($saldo['detalle_info']['descripcion'])) {
                        $ingreso = SaldoGrupo::ingreso($saldo, $empleado);
                        $gasto = SaldoGrupo::gasto($saldo, $empleado);
                        $row = SaldoGrupo::guardar_arreglo($id,$ingreso,$gasto,$saldo);
                        $results[$id] = $row;
                        $id++;
                } else {
                    $ingreso = SaldoGrupo::ingreso($saldo, $empleado);
                    $gasto = SaldoGrupo::gasto($saldo, $empleado);
                    $row = SaldoGrupo::guardar_arreglo($id,$ingreso,$gasto,$saldo);
                    $results[$id] = $row;
                    $id++;
                }
            }
        }
        return $results;
    }
    private static function guardar_arreglo($id,$ingreso,$gasto,$saldo){
        $row = [];
       // $saldo =0;
        $row['item'] = $id + 1;
        $row['fecha'] = $saldo['created_at'];
        $row['fecha_creacion'] = $saldo['created_at'];
        $row['descripcion'] = SaldoGrupo::descripcion_saldo($saldo);
        $row['observacion'] = SaldoGrupo::observacion_saldo($saldo);
        $row['num_comprobante'] = SaldoGrupo::num_comprobante($saldo);
        $row['ingreso'] = $ingreso;
        $row['gasto'] = $gasto;
       // $row['saldo_count'] = $ingreso -$gasto;
        return $row;
    }
    private static function saldoAnterior($id, $fecha, $saldo_anterior)
    {
        $row = [];
        $row['item'] = $id + 1;
        $row['fecha'] = $fecha;
        $row['fecha_creacion'] = $saldo_anterior == null ? $fecha : $saldo_anterior->created_at;
        $row['descripcion'] = 'Saldo Anterior';
        $row['observacion'] ='';
        $row['num_comprobante'] = '';
        $row['ingreso'] = 0;
        $row['gasto'] = 0;
        $row['saldo'] = $saldo_anterior == null ? 0 : $saldo_anterior->saldo_actual;
        return $row;
    }

   /**
    * La función "ingreso" comprueba varias condiciones y devuelve el importe correspondiente en
    * función de los parámetros dados.
    *
    * @param saldo Una matriz que contiene información sobre un saldo o crédito.
    * @param empleado El parámetro "empleado" representa el ID de un empleado.
    *
    * @return el valor de la clave 'monto' de la matriz  si se establece la clave
    * 'descripcion_acreditacion'. En caso contrario, comprueba si el array 'detalle_info' tiene clave
    * 'descripcion' y si la clave 'estado' es igual a 4. Si se cumplen ambas condiciones, devuelve el
    * valor de la clave 'total' del registro
    */
    private static function ingreso($saldo, $empleado)
    {
        if (isset($saldo['descripcion_acreditacion'])) {
            return $saldo['monto'];
        }

        if (isset($saldo['detalle_info']['descripcion'])) {
            if ($saldo['estado'] == 4) {
                return $saldo['total'];
            }
        }

        if (isset($saldo['usuario_recibe_id'])) {
            if ($saldo['usuario_recibe_id'] == $empleado)
                return $saldo['monto'];
        }
        return 0;
    }
    // verifica si es un egreso
    private static function gasto($saldo, $empleado)
    {
        if (isset($saldo['detalle_info']['descripcion'])) {
            if ($saldo['estado'] == 1 || $saldo['estado'] == 4) {
                return  $saldo['total'];
            }
        }
        if (isset($saldo['usuario_envia_id'])) {
            if ($saldo['usuario_envia_id'] == $empleado) {
                return $saldo['monto'];
            }
        }
        return 0;
    }
    private static function descripcion_saldo($saldo)
    {
        if (isset($saldo['descripcion_acreditacion'])) {
            return 'ACREDITACION: ' . $saldo['descripcion_acreditacion'];
        }
        if (isset($saldo['motivo'])) {
            $usuario_envia = Empleado::where('id', $saldo['usuario_envia_id'])->first();
            $usuario_recibe = Empleado::where('id', $saldo['usuario_recibe_id'])->first();
            return 'TRANSFERENCIA DE  ' . $usuario_envia->nombres . ' ' . $usuario_envia->apellidos . ' a ' . $usuario_recibe->nombres . ' ' . $usuario_recibe->apellidos;
        }
        if (isset($saldo['detalle_info']['descripcion'])) {
            if ($saldo['estado'] == 1 || $saldo['estado'] == 4 ) {
                if ($saldo['estado'] == 4) {
                    $sub_detalle_info = SaldoGrupo::subdetalle_info($saldo['sub_detalle_info']);
                    return 'ANULACIÓN DE GASTO: ' . $saldo['detalle_info']['descripcion'] . ': ' . $sub_detalle_info;
                }
                $sub_detalle_info = SaldoGrupo::subdetalle_info($saldo['sub_detalle_info']);
                return $saldo['detalle_info']['descripcion'] . ': ' . $sub_detalle_info;
            }

        }
        return '';
    }
    private static function observacion_saldo($saldo){
        if (isset($saldo['observacion'])) {
            return $saldo['observacion'];
        }
        return '';
    }
    private static function num_comprobante($saldo){
        if (isset($saldo['cuenta'])) {
            return $saldo['cuenta'];
        }
        if (isset($saldo['factura'])) {
            return $saldo['factura'];
        }
        if(isset($saldo['id_saldo'])){
            return $saldo['id_saldo'];
        }
        return '';
    }
    private static function subdetalle_info($subdetalle_info)
    {
        $descripcion = '';
        $i = 0;
        foreach ($subdetalle_info as $sub_detalle) {
            $descripcion .= $sub_detalle['descripcion'];
            $i++;
            if ($i !== count($subdetalle_info)) {
                $descripcion .= ', ';
            }
        }
        return $descripcion;
    }
    public static function empaquetarListado($saldos, $tipo)
    {
        $results = [];
        $id = 0;
        $row = [];
        if (isset($saldos)) {
            switch ($tipo) {
                case 'todos':
                    foreach ($saldos as $saldo) {
                        if ($saldo->usuario->estado == 1 && $saldo->usuario->user->id != 1) {
                            $row['item'] = $id + 1;
                            $row['id'] = $saldo->id;
                            $row['fecha'] = $saldo->fecha;
                            $row['tipo_saldo'] = $saldo->id_tipo_saldo;
                            $row['usuario'] = $saldo->id_usuario;
                            $row['empleado_info'] = $saldo->usuario->user;
                            $row['cargo'] = $saldo->usuario->cargo != null ? $saldo->usuario->cargo->nombre : '';
                            $row['empleado'] = $saldo->usuario;
                            $row['localidad'] = $saldo->usuario->canton != null ? $saldo->usuario->canton->canton : '';
                         //   $row['descripcion_saldo'] = $saldo->descripcion_saldo;
                            $row['saldo_anterior'] = $saldo->saldo_anterior;
                            $row['saldo_depositado'] = $saldo->saldo_depositado;
                            $row['saldo_actual'] = $saldo->saldo_actual;
                            $row['fecha_inicio'] = $saldo->fecha_inicio;
                            $row['fecha_fin'] = $saldo->fecha_fin;
                            $results[$id] = $row;
                            $id++;
                        }
                    }
                    usort($results, __CLASS__ . "::ordenar_por_nombres_apellidos");
                    break;
                case 'usuario':
                    $row['item'] = 1;
                    $row['id'] = $saldos->id;
                    $row['fecha'] = $saldos->fecha;
                    $row['tipo_saldo'] = $saldos->id_tipo_saldo;
                    $row['usuario'] = $saldos->id_usuario;
                    $row['empleado_info'] = $saldos->usuario->user;
                    $row['empleado'] = $saldos->usuario;
                    $row['cargo'] =  $saldos->usuario->cargo != null ? $saldos->usuario->cargo->nombre : '';
                    $row['localidad'] = $saldos->usuario->canton != null ? $saldos->usuario->canton->canton : '';
                    $row['descripcion_saldo'] = $saldos->descripcion_saldo;
                    $row['saldo_anterior'] = $saldos->saldo_anterior;
                    $row['saldo_depositado'] = $saldos->saldo_depositado;
                    $row['saldo_actual'] = $saldos->saldo_actual;
                    $row['fecha_inicio'] = $saldos->fecha_inicio;
                    $row['fecha_fin'] = $saldos->fecha_fin;
                    $results[$id] = $row;
                    break;
            }
        }
        return $results;
    }
    private static function  ordenar_por_nombres_apellidos($a, $b)
    {
        $nameA = $a['empleado']->apellidos . ' ' . $a['empleado']->nombres;
        $nameB = $b['empleado']->apellidos . ' ' . $b['empleado']->nombres;
        return strcmp($nameA, $nameB);
    }
}
