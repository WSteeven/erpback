<?php

namespace Src\App\FondosRotativos;

use App\Http\Requests\FondosRotativos\AjusteSaldoFondoRotativoRequest;
use App\Models\Empleado;
use App\Models\FondosRotativos\AjusteSaldoFondoRotativo;
use App\Models\FondosRotativos\Gasto\Gasto;
use App\Models\FondosRotativos\Saldo\Acreditaciones;
use App\Models\FondosRotativos\Saldo\EstadoAcreditaciones;
use App\Models\FondosRotativos\Saldo\SaldoGrupo;
use App\Models\FondosRotativos\Saldo\Saldo;
use App\Models\FondosRotativos\Saldo\Transferencias;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use PhpParser\Node\Expr\Cast\Array_;
use Ramsey\Uuid\Type\Decimal;

class SaldoService
{
    private $fechaInicio;
    private $fechaFin;
    private $idEmpleado;
    public const INGRESO = 'INGRESO';
    public const EGRESO = 'EGRESO';
    public const AJUSTE = 'AJUSTE';
    public const ANULACION = 'ANULACION';

    public function __construct()
    {
    }
    public function setFechaInicio($fechaInicio)
    {
        $this->fechaInicio = $fechaInicio;
    }
    public function setFechaFin($fechaFin)
    {
        $this->fechaFin = $fechaFin;
    }
    public function setIdEmpleado($idEmpleado)
    {
        $this->idEmpleado = $idEmpleado;
    }
    public function getFechaMesAnterior($fecha = null)
    {
        $fechaInicio = new Carbon($fecha);
        $fechasMesAnterior = [
            'inicio' => $fechaInicio->copy()->subMonth()->startOfMonth()->format('d-m-Y'),
            'fin' =>  $fechaInicio->copy()->subMonth()->endOfMonth()->format('d-m-Y'),
        ];
        return $fechasMesAnterior;
    }
    public function SaldoEstadoCuenta($fechaInicio = null, $fechaFin = null, $id_empleado = null)
    {
        // Si las fechas no se proporcionan, usa las propiedades de la clase
        $fechaInicio = $fechaInicio ?? $this->fechaInicio;
        $fechaFin = $fechaFin ?? $this->fechaFin;

        $fechaInicio = Carbon::createFromFormat('d-m-Y', $fechaInicio)->format('Y-m-d');
        $fechaFin = Carbon::createFromFormat('d-m-Y', $fechaFin)->format('Y-m-d');

        // Obtén el saldo anterior de manera más eficiente
        $saldo_anterior = SaldoGrupo::where('id_usuario', $id_empleado)
            ->where('fecha', '<=', Carbon::parse($fechaInicio)->subDay())
            ->latest('created_at')->first();

        $saldo_anterior = $saldo_anterior ? $saldo_anterior->saldo_actual : 0;

        // Calcula las sumas directamente en las consultas
        $acreditaciones = Acreditaciones::where('id_usuario', $id_empleado)
            ->where('id_estado', EstadoAcreditaciones::REALIZADO)
            ->whereBetween('fecha', [$fechaInicio, $fechaFin])
            ->sum('monto');

        $transferencia_enviadas = Transferencias::where('usuario_envia_id', $id_empleado)
            ->where('estado', 1)
            ->whereBetween('fecha', [$fechaInicio, $fechaFin])
            ->sum('monto');

        $transferencia_recibida = Transferencias::where('usuario_recibe_id', $id_empleado)
            ->where('estado', 1)
            ->whereBetween('fecha', [$fechaInicio, $fechaFin])
            ->sum('monto');

        $gastos = Gasto::where('estado', 1)
            ->where('id_usuario', $id_empleado)
            ->whereBetween('fecha_viat', [$fechaInicio, $fechaFin])
            ->sum('total');

        // Calcula el saldo actual de manera más eficiente
        $saldo_actual = $saldo_anterior + ($acreditaciones - $transferencia_enviadas + $transferencia_recibida - $gastos);

        return $saldo_actual;
    }
    public function SaldoEstadoCuentaArrastre($fechaInicio = null, $fechaFin = null, $id_empleado = null)
    {
        // Si las fechas no se proporcionan, usa las propiedades de la clase
        $fechaInicio = $fechaInicio ?? $this->fechaInicio;
        $fechaFin = $fechaFin ?? $this->fechaFin;

        $fechaInicio = Carbon::createFromFormat('d-m-Y', $fechaInicio)->format('Y-m-d');
        $fechaFin = Carbon::createFromFormat('d-m-Y', $fechaFin)->format('Y-m-d');

        // Obtén el saldo anterior de manera más eficiente
        $saldo_anterior_reporte = $this->EstadoCuentaAnterior($fechaInicio, $id_empleado);
        $saldo_anterior = $saldo_anterior_reporte != 0 ? $saldo_anterior_reporte :
            SaldoGrupo::where('id_usuario', $id_empleado)
            ->where('fecha', '<=', Carbon::parse($fechaInicio)->subDay())
            ->latest('created_at')->first()->saldo_actual;

        $saldo_anterior = $saldo_anterior !== null ? $saldo_anterior : 0;

        // Calcula las sumas directamente en las consultas
        $acreditaciones = Acreditaciones::where('id_usuario', $id_empleado)
            ->where('id_estado', EstadoAcreditaciones::REALIZADO)
            ->whereBetween('fecha', [$fechaInicio, $fechaFin])
            ->sum('monto');

        $transferencia_enviadas = Transferencias::where('usuario_envia_id', $id_empleado)
            ->where('estado', 1)
            ->whereBetween('fecha', [$fechaInicio, $fechaFin])
            ->sum('monto');

        $transferencia_recibida = Transferencias::where('usuario_recibe_id', $id_empleado)
            ->where('estado', 1)
            ->whereBetween('fecha', [$fechaInicio, $fechaFin])
            ->sum('monto');

        $gastos = Gasto::where('estado', 1)
            ->where('id_usuario', $id_empleado)
            ->whereBetween('fecha_viat', [$fechaInicio, $fechaFin])
            ->sum('total');

        // Calcula el saldo actual de manera más eficiente
        $saldo_actual = $saldo_anterior + ($acreditaciones - $transferencia_enviadas + $transferencia_recibida - $gastos);

        return $saldo_actual;
    }

    public static function guardarSaldo($entidad, $data)
    {
        try {
            DB::beginTransaction();
            $saldo_anterior = Saldo::where('empleado_id', $data['empleado_id'])->orderBy('id', 'desc')->first();
            $total_saldo_actual = $saldo_anterior !== null ? $saldo_anterior->saldo_actual : 0;
            $nuevo_saldo = ($data['tipo'] === self::INGRESO) ?
                (array('monto' => ($total_saldo_actual + $data['monto']), 'tipo_saldo' => $data['tipo'])) : (array('monto' => ($total_saldo_actual - $data['monto']), 'tipo_saldo' => $data['tipo']));
            $entidad->saldoFondoRotativo()->create([
                'fecha' => $data['fecha'],
                'saldo_anterior' => $total_saldo_actual,
                'saldo_depositado' => $data['monto'],
                'saldo_actual' => $nuevo_saldo['monto'],
                'tipo_saldo' => $nuevo_saldo['tipo_saldo'],
                'empleado_id' => $data['empleado_id']
            ]);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
    public static function obtenerSaldoActual(Empleado $empleado)
    {
        $saldo_actual = Saldo::where('empleado_id', $empleado->id)->orderBy('id', 'desc')->first();
        $saldo_actual = $saldo_actual != null ? $saldo_actual->saldo_actual : 0;
        return $saldo_actual;
    }
    public static function anularSaldo($entidad, $data)
    {
        try {
            DB::beginTransaction();
            $saldo_anterior = Saldo::where('empleado_id', $data['empleado_id'])->orderBy('id', 'desc')->first();
            $total_saldo_actual = $saldo_anterior !== null ? $saldo_anterior->saldo_actual : 0;
            $nuevo_saldo = ($data['tipo'] === self::INGRESO) ?
                (array('monto' => ($total_saldo_actual + $data['monto']), 'tipo_saldo' => self::ANULACION)) : (array('monto' => ($total_saldo_actual - $data['monto']), 'tipo_saldo' => self::ANULACION));
            $entidad->saldoFondoRotativo()->create([
                'fecha' => $data['fecha'],
                'saldo_anterior' => $total_saldo_actual,
                'saldo_depositado' => $data['monto'],
                'saldo_actual' => $nuevo_saldo['monto'],
                'tipo_saldo' => $nuevo_saldo['tipo_saldo'],
                'empleado_id' => $data['empleado_id']
            ]);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
    public static function ajustarSaldo($data)
    {
        $empleado_id = $data['destinatario_id'];
        $fecha = Carbon::now();
        $monto = $data['monto'];
        $tipo = $data['tipo'];
        $saldo_anterior = Saldo::where('id_usuario', $empleado_id)->orderBy('id', 'desc')->first();
        $total_saldo_actual = $saldo_anterior !== null ? $saldo_anterior->saldo_actual : 0;
        $saldo = new Saldo();
        $saldo->fecha = $fecha;
        $saldo->saldo_anterior = $total_saldo_actual;
        $saldo->saldo_depositado = $monto;
        $nuevo_saldo = ($tipo == AjusteSaldoFondoRotativo::INGRESO) ?
            (array('monto' => ($total_saldo_actual + $monto), 'descripcion' => $tipo)) : (array('monto' => ($total_saldo_actual - $monto), 'descripcion' => $tipo));
        $saldo->saldo_actual =  $nuevo_saldo['monto'];
        $saldo->fecha_inicio = self::calcular_fechas(date('Y-m-d', strtotime($fecha)))[0];
        $saldo->fecha_fin = self::calcular_fechas(date('Y-m-d', strtotime($fecha)))[1];;
        $saldo->id_usuario = $empleado_id;
        $saldo->tipo_saldo = $nuevo_saldo['descripcion'];
        $saldo->save();
    }
    private static function calcular_fechas($fecha)
    {
        $array_dias['Sunday'] = 0;
        $array_dias['Monday'] = 1;
        $array_dias['Tuesday'] = 2;
        $array_dias['Wednesday'] = 3;
        $array_dias['Thursday'] = 4;
        $array_dias['Friday'] = 5;
        $array_dias['Saturday'] = 6;

        $dia_actual = $array_dias[date('l', strtotime($fecha))];

        $rest = $dia_actual + 1;
        $sum = 5 - $dia_actual;
        $fechaIni = date("Y-m-d", strtotime($fecha . "-$rest days"));
        $fechaFin = date("Y-m-d", strtotime($fecha . "+$sum days"));
        return array($fechaIni, $fechaFin);
    }

    public function EstadoCuentaAnterior($fechaInicio = null, $id_empleado)
    {
        $fechaInicio = $fechaInicio ?? $this->fechaInicio;
        $mesAnterior = $this->getFechaMesAnterior($fechaInicio);
        return $this->SaldoEstadoCuenta($mesAnterior['inicio'], $mesAnterior['fin'], $id_empleado);
    }
    public static function obtenerSaldoEmpleadoFecha($fecha_anterior,  $empleado_id)
    {
        $saldo_grupo = SaldoGrupo::where('id_usuario', $empleado_id)
            ->where('fecha', $fecha_anterior)
            ->first();

        if ($saldo_grupo) {
            return $saldo_grupo;
        } else {
            $saldo_fondos = Saldo::where('empleado_id', $empleado_id)
                ->where('fecha', $fecha_anterior)
                ->first();
            return  $saldo_fondos;
        }
    }
    public static function obtenerSaldoEmpleadoEntreFechas($fecha_inicio, $fecha_fin, int $empleado_id)
    {
        $saldo_grupo = SaldoGrupo::where('id_usuario', $empleado_id)
            ->whereBetween('fecha', [$fecha_inicio, $fecha_fin])
            ->orderBy('id', 'desc')
            ->first();
        if ($saldo_grupo) {
            return $saldo_grupo;
        } else {
            $saldo_fondos = Saldo::where('empleado_id', $empleado_id)
                ->whereBetween('fecha', [$fecha_inicio, $fecha_fin])
                ->orderBy('id', 'desc')
                ->first();
            return  $saldo_fondos;
        }
    }
    public static function obtenerSaldoActualUltimaFecha($fecha, int $empleado_id)
    {
        $saldo_grupo = SaldoGrupo::where('id_usuario', $empleado_id)
            ->where('fecha', '>=', $fecha)
            ->orderBy('id', 'desc')
            ->first();

        if ($saldo_grupo) {
            $saldo_grupo = SaldoGrupo::where('id_usuario', $empleado_id)
                ->where('fecha', '<=', $fecha)
                ->orderBy('id', 'desc')
                ->first();
            return $saldo_grupo;
        } else {
            $saldo_fondos = Saldo::where('empleado_id', $empleado_id)
                ->where('fecha', '<=', $fecha)
                ->orderBy('id', 'desc')
                ->first();
            Log::channel('testing')->info('Log', ['saldo', $fecha, $saldo_grupo]);

            return  $saldo_fondos;
        }
    }
    public static function obtenerSaldoAnterior(int $empleado_id, $fecha_anterior, $fecha_inicio = null)
    {

        $saldo_grupo = SaldoGrupo::where('id_usuario', $empleado_id)
            ->where('fecha', '>=', $fecha_anterior)
            ->orderBy('id', 'desc')
            ->first();
        if ($saldo_grupo) {
            $saldo_grupo = SaldoGrupo::where('id_usuario', $empleado_id)
            ->where('fecha', '<=', $fecha_anterior)
            ->orderBy('created_at', 'DESC')
            ->first();
            return $saldo_grupo;
        } else {
            $saldo_fondos = null;
            if($fecha_inicio == null){
                $saldo_fondos = Saldo::where('empleado_id', $empleado_id)
                ->where('fecha', '<=', $fecha_anterior)
                ->where('fecha', '<', $fecha_inicio)
                ->orderBy('created_at', 'DESC')
                ->first();
            }
            $saldo_fondos = $saldo_fondos  || !is_null($saldo_fondos)?  $saldo_fondos: Saldo::where('empleado_id', $empleado_id)
            ->where('fecha', $fecha_anterior)
            ->orderBy('created_at', 'DESC')
            ->first();
            $saldo_fondos = $saldo_fondos?$saldo_fondos: Saldo::where('empleado_id', $empleado_id)
                ->where('fecha', '<=', $fecha_anterior)
                ->orderBy('created_at', 'DESC')
                ->first();
            $saldo_fondos = $saldo_fondos?->tipo_saldo == self::ANULACION ? Saldo::where('empleado_id', 14)
                ->where('fecha', '<=', $fecha_anterior)
                ->where('tipo_saldo', '!=', self::ANULACION)
                ->orderBy('created_at', 'DESC')
                ->first() : $saldo_fondos;
            return  $saldo_fondos;
        }
    }
    public static function empaquetarSaldoableReporteAntiguo(Collection $saldos_fondos, SupportCollection $array_ids)
    {
        $results = [];
        $id = 0;
        foreach ($saldos_fondos as $saldo) {
            if (!is_null($saldo->saldoabLe_id)) {
                if (!in_array($saldo->saldoable->id, $array_ids->toArray())) {
                    $results[$id] = $saldo->saldoable;
                    $array_ids[] = $saldo->saldoable->id;
                    $results[$id]['tipo'] = $saldo->tipo_saldo;
                    $id++;
                }
            }
        }
        return collect($results);
    }
    public static function empaquetarSaldoable(Collection $saldos_fondos)
    {
        $results = [];
        $id = 0;
        foreach ($saldos_fondos as $saldo) {
            $results[$id] = $saldo->saldoable;
            $array_ids[] = $saldo->saldoable->id;
            $results[$id]['tipo'] = $saldo->tipo_saldo;
            $id++;
        }
        return $results;
    }
    public static function existeSaldoNuevaTabla($fecha, $empleado_id)
    {
        $registros_saldos = Saldo::where('fecha', $fecha)->where('empleado_id', $empleado_id)->get();
        $cantidad_registros_saldos = $registros_saldos->count();
        return $cantidad_registros_saldos > 0;
    }
    private static function guardarArreglo($id, $ingreso, $gasto, $saldo)
    {
        $row = [];
        // $saldo =0;
        $row['item'] = $id + 1;
        $row['fecha'] = isset($saldo['fecha_viat']) ? $saldo['fecha_viat'] : (isset($saldo['created_at']) ? $saldo['created_at'] : $saldo['fecha']);
        $row['fecha_creacion'] = $saldo['updated_at'];
        $row['descripcion'] = SaldoGrupo::descripcionSaldo($saldo);
        $row['observacion'] = SaldoGrupo::observacionSaldo($saldo);
        $row['num_comprobante'] = SaldoGrupo::obtenerNumeroComprobante($saldo);
        $row['ingreso'] = $ingreso;
        $row['gasto'] = $gasto;
        // $row['saldo_count'] = $ingreso -$gasto;
        return $row;
    }
}
