<?php

namespace Src\App\FondosRotativos;

use App\Http\Requests\FondosRotativos\AjusteSaldoFondoRotativoRequest;
use App\Models\FondosRotativos\AjusteSaldoFondoRotativo;
use App\Models\FondosRotativos\Gasto\Gasto;
use App\Models\FondosRotativos\Saldo\Acreditaciones;
use App\Models\FondosRotativos\Saldo\EstadoAcreditaciones;
use App\Models\FondosRotativos\Saldo\SaldoGrupo;
use App\Models\FondosRotativos\Saldo\SaldosFondosRotativos;
use App\Models\FondosRotativos\Saldo\Transferencias;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
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

    public static function guardarSaldo($entidad,$data)
    {
        try {
            DB::beginTransaction();
            $saldo_anterior = SaldosFondosRotativos::where('empleado_id', $data['empleado_id'])->orderBy('id', 'desc')->first();
            $total_saldo_actual = $saldo_anterior !== null ? $saldo_anterior->saldo_actual : 0;
            $nuevo_saldo = ($data['tipo'] == self::INGRESO || self::ANULACION) ?
                (array('monto' => ($total_saldo_actual + $data['monto']), 'tipo_saldo' => $data['tipo'])) : (array('monto' => ($total_saldo_actual - $data['monto']), 'tipo_saldo' => $data['tipo']));
            $entidad->saldoFondoRotativo()->create([
                'fecha' => $data['fecha'],
                'saldo_anterior' => is_null($saldo_anterior) ? 0 : $saldo_anterior,
                'saldo_depositado' => $data['monto'],
                'saldo_actual' => $nuevo_saldo['monto'],
                'tipo_saldo' => $nuevo_saldo['tipo_saldo'],
                'empleado_id'=>$data['empleado_id']
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
        $saldo_anterior = SaldoGrupo::where('id_usuario', $empleado_id)->orderBy('id', 'desc')->first();
        $total_saldo_actual = $saldo_anterior !== null ? $saldo_anterior->saldo_actual : 0;
        $saldo = new SaldoGrupo();
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
}
