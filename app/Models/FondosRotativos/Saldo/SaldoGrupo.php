<?php

namespace App\Models\FondosRotativos\Saldo;

use App\Models\Empleado;
use App\Models\FondosRotativos\AjusteSaldoFondoRotativo;
use App\Models\FondosRotativos\Gasto\Gasto;
use Carbon\Carbon;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

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
    public static function empaquetarCombinado($nuevo_elemento,$arreglo, $empleado)
    {
        $results = [];
        $id = 1;
        $row = [];
        $results[0] = $nuevo_elemento;
        if (isset($arreglo)) {
            $id += 1;
            foreach ($arreglo as $saldo) {
                $ingreso = SaldoGrupo::ingreso($saldo, $empleado);
                $gasto = SaldoGrupo::gasto($saldo, $empleado);
                $row = SaldoGrupo::guardarArreglo($id, $ingreso, $gasto, $saldo, $empleado);
                $results[$id] = $row;
                $id++;
            }
        }
        return $results;
    }
    private static function guardarArreglo($id, $ingreso, $gasto, $saldo, $empleado)
    {
        $row = [];
        // $saldo =0;
        $row['item'] = $id + 1;
        $row['fecha'] = isset($saldo['fecha_viat']) ? $saldo['fecha_viat'] : (isset($saldo['created_at']) ? $saldo['created_at'] : $saldo['fecha']);
        $row['fecha_creacion'] = $saldo['updated_at'];
        $row['descripcion'] = SaldoGrupo::descripcionSaldo($saldo, $empleado);
        $row['observacion'] = SaldoGrupo::observacionSaldo($saldo, $empleado);
        $row['num_comprobante'] = SaldoGrupo::obtenerNumeroComprobante($saldo);
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
        $row['observacion'] = '';
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
        switch (get_class($saldo)) {
            case Gasto::class:
                if ($saldo['estado'] === Gasto::ANULADO) {
                    return $saldo['total'];
                }
                break;
            case Acreditaciones::class:
                return $saldo['monto'];
                break;
            case Transferencias::class:
                if ($saldo['estado'] === Transferencias::ANULADO) {
                    if ($saldo['usuario_envia_id'] == $empleado) {
                        return $saldo['monto'];
                    }
                }
                if ($saldo['estado'] === Transferencias::APROBADO) {
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
    private static function gasto($saldo, $empleado)
    {
        switch (get_class($saldo)) {
            case Gasto::class:
                if (isset($saldo['tipo'])) {
                    if ($saldo['tipo'] === 'EGRESO') {
                        return $saldo['total'];
                    }
                }
                return $saldo['total'];
                break;
            case Acreditaciones::class:
                if ($saldo['estado'] === EstadoAcreditaciones::ANULADO) {
                    return $saldo['monto'];
                }
                break;
            case Transferencias::class:
                if ($saldo['usuario_envia_id'] == $empleado) {
                    return $saldo['monto'];
                }
                if ($saldo['estado'] === Transferencias::ANULADO) {
                    if ($saldo['usuario_recibe_id'] == $empleado) {
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
    private static function descripcionSaldo($saldo, $empleado)
    {
        switch (get_class($saldo)) {
            case Gasto::class:
                $sub_detalle_info = self::subDetalleInfo($saldo->subDetalle);
                if ($saldo['estado'] === Gasto::APROBADO) {
                    return $saldo['detalle_info']['descripcion'] . ': ' . $sub_detalle_info;
                }
                if ($saldo['estado'] === Gasto::ANULADO) {
                    return 'ANULACIÓN DE GASTO: ' . $saldo['detalle_info']['descripcion'] . ': ' . $sub_detalle_info;
                }
                break;
            case Acreditaciones::class:
                return $saldo['descripcion_acreditacion'];
                break;
            case Transferencias::class:
                $usuario_envia = Empleado::where('id', $saldo['usuario_envia_id'])->first();
                $usuario_recibe = Empleado::where('id', $saldo['usuario_recibe_id'])->first();
                if ($saldo['estado'] ===  Transferencias::APROBADO) {
                    return 'TRANSFERENCIA DE  ' . self::empleadoTransferencia($saldo['usuario_envia_id']) . ' a ' . self::empleadoTransferencia($saldo['usuario_recibe_id']);
                }
                if ($saldo['estado'] === Transferencias::ANULADO) {
                    if ($saldo['usuario_envia_id'] == $empleado) {
                        return 'ANULACION: TRANSFERENCIA DE  ' . $usuario_envia->nombres . ' ' . $usuario_envia->apellidos . ' a ' . $usuario_recibe->nombres . ' ' . $usuario_recibe->apellidos;
                    }
                }
                break;
            case AjusteSaldoFondoRotativo::class:
                return $saldo['motivo'];
                break;
        }
        return '';
    }
    private static function observacionSaldo($saldo, $empleado)
    {

        switch (get_class($saldo)) {
            case Gasto::class:
                if ($saldo['estado'] === Gasto::APROBADO) {
                    return $saldo['observacion'];
                }
                if ($saldo['estado'] === Gasto::ANULADO) {
                    return $saldo['observacion_anulacion'];
                }
                break;
            case Acreditaciones::class:
                if ($saldo['estado'] === EstadoAcreditaciones::ANULADO) {
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
    private static function subDetalleInfo($subdetalle_info)
    {
        if (!is_null($subdetalle_info)) {
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
                    usort($results, __CLASS__ . "::ordenarNombresApellidos");
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
    //Relación polimorfica
    public function saldo_grupo()
    {
        return $this->morphTo();
    }

    public static function crearSaldoGrupo($fecha, $saldo_anterior, $saldo_depositado, $saldo_actual, $fecha_inicio, $fecha_fin, $id_usuario, $tipo_saldo, $entidad)
    {
        $saldo_grupo = $entidad->saldo_grupo()->create([
            'fecha' => $fecha,
            'saldo_anterior' => $saldo_anterior,
            'saldo_depositado' => $saldo_depositado,
            'saldo_actual' => $saldo_actual,
            'tipo_saldo' => $tipo_saldo,
            'fecha_inicio' => $fecha_inicio,
            'fecha_fin' => $fecha_fin,
            'id_usuario' => $id_usuario
        ]);
        return $saldo_grupo;
    }


    /**
     * La función `verificarGastosRepetidosEnSaldoGrupo` busca registros duplicados en un cobro de
     * gastos basándose en criterios específicos y ajusta el cobro en consecuencia.
     *
     * @param gastos La función `verificarGastosRepetidosEnSaldoGrupo` está diseñada para verificar si
     * hay registros duplicados en la tabla `SaldoGrupo` basándose en ciertas condiciones. Itera sobre
     * el array de `` y busca entradas duplicadas en la tabla `SaldoGrupo`.
     *
     * @return La función `verificarGastosRepetidosEnSaldoGrupo` devuelve el array de `` después
     * de verificar y manejar cualquier registro duplicado en la tabla `SaldoGrupo` según ciertas
     * condiciones.
     */
    public static function verificarGastosRepetidosEnSaldoGrupo($gastos)
    {
        try {
            //code...
            $registro_saldo_grupo_duplicado = false;
            foreach ($gastos as $index => $gasto) {
                if ($registro_saldo_grupo_duplicado) {
                    $registro_saldo_grupo_duplicado = false;
                    continue;
                }
                $registros = SaldoGrupo::where('id_usuario', $gasto->id_usuario)
                    ->where('saldo_depositado', $gasto->total)
                    ->whereBetween('created_at', [
                        Carbon::parse($gasto->updated_at)->subSecond(2),
                        Carbon::parse($gasto->updated_at)->addSecond(2),
                    ])->get();
                if ($registros->count() > 1) {
                    $gastos->splice($index, 0, [$gasto]);
                    $registro_saldo_grupo_duplicado = true;
                }
            }
            return $gastos;
        } catch (\Throwable $th) {
            Log::channel('testing')->info('Log', ['error en verificarRepetidos', $th->getMessage(), $th->getLine()]);
            throw $th;
        }
    }
    public static function empleadoTransferencia($empleado_id)
    {
        $empleado = Empleado::find($empleado_id);
        return $empleado?->nombres . ' ' . $empleado?->apellidos;
    }
}
