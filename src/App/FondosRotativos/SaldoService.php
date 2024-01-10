<?php

namespace Src\App\FondosRotativos;

use App\Models\FondosRotativos\Gasto\Gasto;
use App\Models\FondosRotativos\Saldo\Acreditaciones;
use App\Models\FondosRotativos\Saldo\EstadoAcreditaciones;
use App\Models\FondosRotativos\Saldo\SaldoGrupo;
use App\Models\FondosRotativos\Saldo\Transferencias;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class SaldoService
{
    private $fechaInicio;
    private $fechaFin;
    private $idEmpleado;
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
        $saldo_anterior = $saldo_anterior_reporte !=0? $saldo_anterior_reporte :
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


    public function EstadoCuentaAnterior($fechaInicio = null, $id_empleado)
    {
        $fechaInicio = $fechaInicio ?? $this->fechaInicio;
        $mesAnterior = $this->getFechaMesAnterior($fechaInicio);
        return $this->SaldoEstadoCuenta($mesAnterior['inicio'], $mesAnterior['fin'], $id_empleado);
    }
}
