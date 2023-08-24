<?php

namespace Src\App\FondosRotativos;

use App\Models\FondosRotativos\Gasto\Gasto;
use App\Models\FondosRotativos\Saldo\Acreditaciones;
use App\Models\FondosRotativos\Saldo\EstadoAcreditaciones;
use App\Models\FondosRotativos\Saldo\SaldoGrupo;
use App\Models\FondosRotativos\Saldo\Transferencias;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
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
    public function getFechaMesAnterior()
    {
        $this->fechaInicio = new Carbon($this->fechaInicio);
        $fechasMesAnterior = [
            'inicio' => $this->fechaInicio->copy()->subMonth()->startOfMonth(),
            'fin' => $this->fechaInicio->copy()->subMonth()->endOfMonth(),
        ];
        return $fechasMesAnterior;
    }
    public function SaldoEstadoCuenta($fechaInicio = null, $fechaFin = null)
    {
        $fechaInicio == null ? $this->fechaInicio : $this->fechaInicio;
        $fechaFin == null ? $this->fechaFin : $this->fechaFin;

        $fecha = Carbon::parse($fechaInicio);
        $fecha_anterior =  $fecha->subDay()->format('Y-m-d');
        $saldo_anterior = SaldoGrupo::where('id_usuario', $this->idEmpleado)
            ->where('fecha', '<=', $fecha_anterior)
            ->orderBy('created_at', 'desc')->limit(1)->first();
        if ($saldo_anterior != null) {
            $fecha =  Carbon::parse($saldo_anterior->fecha);
            $fecha_anterior =  $fecha->format('Y-m-d');
        }
        $fecha_anterior =  $fecha->format('Y-m-d');
        //Gastos
        $gastos_reporte = Gasto::with('empleado_info', 'detalle_info', 'sub_detalle_info', 'aut_especial_user')
            ->selectRaw("*, DATE_FORMAT(fecha_viat, '%d/%m/%Y') as fecha")
            ->whereBetween('fecha_viat', [$fechaInicio, $fechaFin])
            ->where('id_usuario', '=', $this->idEmpleado)
            ->where(function ($query) {
                $query->where('estado', '=', 1)
                    ->orWhere('estado', '=', 4);
            })
            ->get();

        //Transferencias
        $transferencias_enviadas = Transferencias::where('usuario_envia_id', $this->idEmpleado)
            ->with('usuario_recibe', 'usuario_envia')
            ->where('estado', 1)
            ->whereBetween('fecha', [$fechaInicio, $fechaFin])
            ->get();
        $transferencias_recibidas = Transferencias::where('usuario_recibe_id', $this->idEmpleado)
            ->with('usuario_recibe', 'usuario_envia')
            ->where('estado', 1)
            ->whereBetween('fecha', [$fechaInicio, $fechaFin])
            ->get();
        //Acreditaciones
        $acreditaciones_reportes = Acreditaciones::with('usuario')
            ->where('id_usuario', $this->idEmpleado)
            ->where('id_estado', EstadoAcreditaciones::REALIZADO)
            ->whereBetween('fecha', [$fechaInicio, $fechaFin])
            ->get();
        $encuadres_saldo = SaldoGrupo::where('id_usuario', $this->idEmpleado)
            ->where('tipo_saldo', 'Encuadre')
            ->whereBetween('fecha', [$fechaInicio, $fechaFin])
            ->get();
        //Unir todos los reportes
        $reportes_unidos = array_merge($gastos_reporte->toArray(), $transferencias_enviadas->toArray(), $transferencias_recibidas->toArray(), $acreditaciones_reportes->toArray(), $encuadres_saldo->toArray());
        $reportes_unidos = SaldoGrupo::empaquetarCombinado($reportes_unidos, $this->idEmpleado, $fecha_anterior, $saldo_anterior);
        $reportes_unidos = collect($reportes_unidos)->sortBy('fecha_creacion')->toArray();
        $saldo_ultimo = $saldo_anterior ? $saldo_anterior['saldo_actual'] : 0;
        $salt_ant = floatval($saldo_anterior != null ? $saldo_anterior->saldo_actual : 0);
        $salt_ant = floatval($salt_ant);
        $saldo_ini =  $saldo_ultimo !=  $salt_ant ?   $saldo_ultimo : $salt_ant;
        $nuevo_elemento = [
            'item' => 1,
            'fecha' => $fecha_anterior,
            'fecha_creacion' =>  $saldo_anterior == null ? $fecha : $saldo_anterior->created_at,
            'num_comprobante' => '',
            'descripcion' => 'SALDO ANTERIOR',
            'observacion' => '',
            'ingreso' => 0,
            'gasto' => 0,
            'saldo' => $saldo_ini
        ];
        $reportes_unidos =  collect($reportes_unidos)
            ->prepend($nuevo_elemento)
            ->toArray();
        foreach ($reportes_unidos as $item) {
            if ($item != null) {
                $saldo_ultimo = $saldo_ultimo + $item['ingreso'] - $item['gasto'];
            }
        }

        return  $saldo_ultimo !=  $salt_ant ?   $saldo_ultimo : $salt_ant;
    }
    public function EstadoCuentaAnterior()
    {
        return $this->SaldoEstadoCuenta($this->getFechaMesAnterior()['inicio'], $this->getFechaMesAnterior()['fin']);
    }
}
