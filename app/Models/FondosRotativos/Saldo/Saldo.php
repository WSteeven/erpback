<?php

namespace App\Models\FondosRotativos\Saldo;

use App\Http\Resources\FondosRotativos\Gastos\GastoResource;
use App\Models\Empleado;
use App\Models\FondosRotativos\AjusteSaldoFondoRotativo;
use App\Models\FondosRotativos\Gasto\Gasto;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
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
    public const INGRESO = 'INGRESO';
    public const EGRESO = 'EGRESO';
    public const ANULACION = 'ANULACION';


    public function empleado()
    {
        return $this->belongsTo(Empleado::class, 'empleado_id');
    }

    public function saldoable()
    {
        return $this->morphTo();
    }

    public static function empaquetarCombinado($arreglo, $empleado)
    {
        $results = [];
        $id = 0;
        $row = [];
        foreach ($arreglo as $saldo) {
            switch (get_class($saldo->saldoable)) {
                case Acreditaciones::class:
                    if ($saldo->saldoable['id_estado'] !== EstadoAcreditaciones::MIGRACION) {
                        $ingreso = Saldo::ingreso($saldo->saldoable, $saldo->tipo_saldo, $empleado);
                        $gasto = Saldo::gasto($saldo->saldoable, $saldo->tipo_saldo, $empleado);
                        $row = Saldo::guardarArreglo($id, $ingreso, $gasto, $saldo->tipo_saldo, $empleado, $saldo->saldoable);
                        $results[$id] = $row;
                        $id++;
                    }
                    break;

                default:
                    $ingreso = Saldo::ingreso($saldo->saldoable, $saldo->tipo_saldo, $empleado);
                    $gasto = Saldo::gasto($saldo->saldoable, $saldo->tipo_saldo, $empleado);
                    $row = Saldo::guardarArreglo($id, $ingreso, $gasto, $saldo->tipo_saldo, $empleado, $saldo->saldoable);
                    $results[$id] = $row;
                    $id++;
                    break;
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
                    $row['empleado'] = $saldos->empleado;
                    $row['cargo'] =  $saldos->empleado->cargo != null ? $saldos->empleado->cargo->nombre : '';
                    $row['localidad'] = $saldos->empleado->canton != null ? $saldos->empleado->canton->canton : '';
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
    private static function ingreso($saldo, $tipo, $empleado)
    {
        switch (get_class($saldo)) {
            case Gasto::class:
                if ($tipo === self::ANULACION) {
                    return $saldo['total'];
                }
                break;
            case Acreditaciones::class:
                return $saldo['monto'];
                break;
            case Transferencias::class:
                if ($tipo === self::ANULACION) {
                    if ($saldo['usuario_envia_id'] == $empleado) {
                        return $saldo['monto'];
                    }
                }
                if ($tipo == self::INGRESO) {
                    if ($saldo['usuario_recibe_id'] == $empleado) {
                        return $saldo['monto'];
                    }
                }

                break;
            case AjusteSaldoFondoRotativo::class:
                if ($saldo['tipo'] == AjusteSaldoFondoRotativo::INGRESO) {
                    return $saldo['monto'];
                }
                break;
        }
        return 0;
    }
    // verifica si es un egreso
    private static function gasto($saldo, $tipo, $empleado)
    {
        switch (get_class($saldo)) {
            case Gasto::class:
                if ($tipo == self::EGRESO) {
                    return $saldo['total'];
                }
                break;
            case Acreditaciones::class:
                if ($tipo == self::ANULACION) {
                    return $saldo['monto'];
                }
                break;
            case Transferencias::class:
                if ($tipo == self::ANULACION) {
                    if ($saldo['usuario_recibe_id'] == $empleado) {
                        return $saldo['monto'];
                    }
                }
                if ($tipo == self::EGRESO) {
                    if ($saldo['usuario_envia_id'] == $empleado) {
                        return $saldo['monto'];
                    }
                }
                break;
            case AjusteSaldoFondoRotativo::class:
                if ($saldo['tipo'] == AjusteSaldoFondoRotativo::EGRESO) {
                    return $saldo['monto'];
                }
                break;
        }
        return 0;
    }
    private static function descripcionSaldo($saldo, $tipo, $empleado)
    {
        switch (get_class($saldo)) {
            case Gasto::class:
                $sub_detalle_info = self::subDetalleInfo($saldo->subDetalle);
                if ($tipo == self::EGRESO) {
                    return $saldo['detalle_info']['descripcion'] . ': ' . $sub_detalle_info;
                }
                if ($tipo == self::ANULACION) {
                    return 'ANULACIÓN DE GASTO: ' . $saldo['detalle_info']['descripcion'] . ': ' . $sub_detalle_info;
                }
                break;
            case Acreditaciones::class:
                if ($tipo == self::INGRESO) {
                    return $saldo['descripcion_acreditacion'];
                }
                if ($tipo == self::ANULACION) {
                    return 'ANULACIÓN DE ACREDITACIÖN: ' . $saldo['descripcion_acreditacion'];
                }
                break;
            case Transferencias::class:
                $usuario_envia = Empleado::where('id', $saldo['usuario_envia_id'])->first();
                $usuario_recibe = Empleado::where('id', $saldo['usuario_recibe_id'])->first();
                if ($tipo === self::ANULACION) {
                    if ($saldo['usuario_envia_id'] == $empleado) {
                        return 'ANULACION: TRANSFERENCIA DE  ' . $usuario_envia->nombres . ' ' . $usuario_envia->apellidos . ' a ' . $usuario_recibe->nombres . ' ' . $usuario_recibe->apellidos;
                    }
                }
                if ($tipo == self::INGRESO) {
                    if ($saldo['usuario_recibe_id'] == $empleado) {
                        return 'TRANSFERENCIA DE  ' . $usuario_recibe->nombres . ' ' . $usuario_recibe->apellidos . ' a ' . $usuario_envia->nombres . ' ' . $usuario_envia->apellidos;
                    }
                }

                if ($tipo == self::EGRESO) {
                    if ($saldo['usuario_envia_id'] == $empleado) {

                        return 'TRANSFERENCIA DE  ' . $usuario_envia->nombres . ' ' . $usuario_envia->apellidos . ' a ' . $usuario_recibe->nombres . ' ' . $usuario_recibe->apellidos;
                    }
                }
                break;
            case AjusteSaldoFondoRotativo::class:
                return $saldo['motivo'];
                break;
        }
        return '';
    }
    private static function observacionSaldo($saldo, $tipo, $empleado)
    {
        switch (get_class($saldo)) {
            case Gasto::class:
                $sub_detalle_info = Saldo::subDetalleInfo($saldo['sub_detalle']);
                if ($tipo == self::EGRESO) {
                    return $saldo['observacion'];
                }
                if ($tipo == self::ANULACION) {
                    return $saldo['observacion_anulacion'];
                }
                break;
            case Acreditaciones::class:
                if ($tipo == self::ANULACION) {
                    return 'ANULACIÓN DE ACREDITACIÖN: ' . $saldo['motivo'];
                }
                break;
            case Transferencias::class:
                return $saldo['observacion'];
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
    private static function subdetalleInfo($subdetalle_info)
    {
        $descripcion = '';
        if (!is_null($subdetalle_info)) {
            $i = 0;
            foreach ($subdetalle_info as $sub_detalle) {
                $descripcion .= $sub_detalle->descripcion;
                $i++;
                if ($i !== count($subdetalle_info)) {
                    $descripcion .= ', ';
                }
            }
        }
        return $descripcion;
    }
    private static function guardarArreglo($id, $ingreso, $gasto, $tipo, $empleado, $saldo)
    {
        $row = [];
        // $saldo =0;
        $row['item'] = $id + 1;
        $row['fecha'] = isset($saldo['fecha_viat']) ? $saldo['fecha_viat'] : (isset($saldo['created_at']) ? $saldo['created_at'] : $saldo['fecha']);
        $row['fecha_creacion'] = $saldo['updated_at'];
        $row['descripcion'] = self::descripcionSaldo($saldo, $tipo, $empleado);
        $row['observacion'] = self::observacionSaldo($saldo, $tipo, $empleado);
        $row['num_comprobante'] = self::obtenerNumeroComprobante($saldo);
        $row['ingreso'] = $ingreso;
        $row['gasto'] = $gasto;
        // $row['saldo_count'] = $ingreso -$gasto;
        return $row;
    }
}
