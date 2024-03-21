<?php

namespace App\Models\FondosRotativos\Saldo;

use App\Models\Empleado;
use App\Models\FondosRotativos\AjusteSaldoFondoRotativo;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class Saldo extends Model  implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;
    use UppercaseValuesTrait;
    protected $table = 'fr_saldos';
    protected $primaryKey = 'id';
    protected $fillable = [
        'fecha',
        'saldo_anterior',
        'saldo_depositado',
        'saldo_actual',
        'tipo_saldo',
        'empleado_id',
    ];

    public function empleado()
    {
        return $this->belongsTo(Empleado::class, 'empleado_id');
    }

    public function saldoable()
    {
        return $this->morphTo();
    }
    public static function empaquetarCombinado($arreglo, $empleado, $fecha, $saldo_anterior)
    {
        $results = [];
        $id = 0;
        $row = [];
        if (isset($arreglo)) {
            //  $results[0] = SaldoGrupo::saldoAnterior($id, $fecha, $saldo_anterior);
            $id += 1;
            $fecha_anterior = $fecha;
            foreach ($arreglo as $saldo) {
                if (isset($saldo['detalle_info']['descripcion'])) {
                    $ingreso = self::ingreso($saldo, $empleado);
                    $gasto = self::gasto($saldo, $empleado);
                    $row = self::guardarArreglo($id, $ingreso, $gasto, $saldo);
                    $results[$id] = $row;
                    $id++;
                } else {
                    $ingreso = self::ingreso($saldo, $empleado);
                    $gasto = self::gasto($saldo, $empleado);
                    $row = self::guardarArreglo($id, $ingreso, $gasto, $saldo);
                    $results[$id] = $row;
                    $id++;
                }
            }
        }
        return $results;
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
                        if ($saldo->empleado->estado == 1 && $saldo->empleado->id != 1) {
                            $row['item'] = $id + 1;
                            $row['id'] = $saldo->id;
                            $row['fecha'] = $saldo->fecha;
                            $row['tipo_saldo'] = $saldo->id_tipo_saldo;
                            $row['usuario'] = $saldo->empleado_id;
                            $row['empleado_info'] = $saldo->empleado->user;
                            $row['cargo'] = $saldo->empleado->cargo != null ? $saldo->empleado->cargo->nombre : '';
                            $row['empleado'] = $saldo->empleado;
                            $row['localidad'] = $saldo->empleado->canton != null ? $saldo->empleado->canton->canton : '';
                            $row['saldo_anterior'] = $saldo->saldo_anterior;
                            $row['saldo_depositado'] = $saldo->saldo_depositado;
                            $row['saldo_actual'] = $saldo->saldo_actual;
                            $results[$id] = $row;
                            $id++;
                        }
                    }
                    usort($results, __CLASS__ . "::ordenarNombresApellidos");
                    break;
                case 'usuario':
                    $row['item'] = 1;
                    $row['id'] = $saldos->id;
                    $row['fecha'] = $saldos->fecha;
                    $row['tipo_saldo'] = $saldos->id_tipo_saldo;
                    $row['usuario'] = $saldos->empleado_id;
                    $row['empleado_info'] = $saldos->usuario->user;
                    $row['empleado'] = $saldos->empleado;
                    $row['cargo'] =  $saldos->empleado->cargo != null ? $saldos->empleado->cargo->nombre : '';
                    $row['localidad'] = $saldos->empleado->canton != null ? $saldos->empleado->canton->canton : '';
                    $row['descripcion_saldo'] = $saldos->descripcionSaldo;
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
    private static function  ordenarNombresApellidos($a, $b)
    {
        $nameA = $a['empleado']->apellidos . ' ' . $a['empleado']->nombres;
        $nameB = $b['empleado']->apellidos . ' ' . $b['empleado']->nombres;
        return strcmp($nameA, $nameB);
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
        if (isset($saldo['tipo'])) {
            if ($saldo['tipo'] == AjusteSaldoFondoRotativo::INGRESO) {
                return $saldo['monto'];
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
        if (isset($saldo['tipo'])) {
            if ($saldo['tipo'] == AjusteSaldoFondoRotativo::EGRESO) {
                return $saldo['monto'];
            }
        }
        return 0;
    }
    private static function descripcionSaldo($saldo)
    {
        if (isset($saldo['descripcion_acreditacion'])) {
            return 'ACREDITACION: ' . $saldo['descripcion_acreditacion'];
        }
        if (isset($saldo['usuario_envia_id'])) {
            $usuario_envia = Empleado::where('id', $saldo['usuario_envia_id'])->first();
            $usuario_recibe = Empleado::where('id', $saldo['usuario_recibe_id'])->first();
            return 'TRANSFERENCIA DE  ' . $usuario_envia->nombres . ' ' . $usuario_envia->apellidos . ' a ' . $usuario_recibe->nombres . ' ' . $usuario_recibe->apellidos;
        }
        if (isset($saldo['motivo'])) {
            return $saldo['motivo'];
        }
        if (isset($saldo['detalle_info']['descripcion'])) {
            if ($saldo['estado'] == 1 || $saldo['estado'] == 4) {
                if ($saldo['estado'] == 4) {
                    $sub_detalle_info = Saldo::subDetalleInfo($saldo['sub_detalle']);
                    return 'ANULACIÓN DE GASTO: ' . $saldo['detalle_info']['descripcion'] . ': ' . $sub_detalle_info;
                }
                $sub_detalle_info = Saldo::subDetalleInfo($saldo['sub_detalle']);
                return $saldo['detalle_info']['descripcion'] . ': ' . $sub_detalle_info;
            }
        }
        return '';
    }
    private static function observacionSaldo($saldo)
    {
        if (isset($saldo['observacion'])) {
            return $saldo['observacion'];
        }
        if (isset($saldo['descripcion'])) {
            return $saldo['descripcion'];
        }
        return '';
    }
    private static function obtenerNumeroComprobante($saldo)
    {
        if (isset($saldo['cuenta'])) {
            return $saldo['cuenta'];
        }
        if (isset($saldo['factura'])) {
            return $saldo['factura'];
        }
        if (isset($saldo['id_saldo'])) {
            return $saldo['id_saldo'];
        }
        return '';
    }
    private static function subDetalleInfo($subdetalle_info)
    {
        if(!is_null($subdetalle_info)){
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
        return '';
    }
    private static function guardarArreglo($id, $ingreso, $gasto, $saldo)
    {
        $row = [];
        // $saldo =0;
        $row['item'] = $id + 1;
        $row['fecha'] = isset($saldo['fecha_viat']) ? $saldo['fecha_viat'] : (isset($saldo['created_at']) ? $saldo['created_at'] : $saldo['fecha']);
        $row['fecha_creacion'] = $saldo['updated_at'];
        $row['descripcion'] = self::descripcionSaldo($saldo);
        $row['observacion'] = self::observacionSaldo($saldo);
        $row['num_comprobante'] = self::obtenerNumeroComprobante($saldo);
        $row['ingreso'] = $ingreso;
        $row['gasto'] = $gasto;
        // $row['saldo_count'] = $ingreso -$gasto;
        return $row;
    }
}
