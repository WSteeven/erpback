<?php

namespace Src\App\RecursosHumanos\NominaPrestamos;

use App\Models\RecursosHumanos\NominaPrestamos\PlazoPrestamoEmpresarial;
use App\Models\RecursosHumanos\NominaPrestamos\PrestamoEmpresarial;
use App\Models\RecursosHumanos\NominaPrestamos\PrestamoHipotecario;
use App\Models\RecursosHumanos\NominaPrestamos\PrestamoQuirorafario;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PrestamoService
{
    private $mes;
    private $id_empleado;

    public function __construct($mes = null)
    {
        $this->mes = $mes == null ? Carbon::now() : $mes;
    }

    public function setMes($mes)
    {

        $this->mes =  $mes;
    }

    public function setEmpleado($id_empleado = null)
    {
        $this->id_empleado = $id_empleado;
    }

    private function query($query, $todos = false, $key_empleado = 'empleado_id')
    {
        if (!$todos) {
            $query->where($key_empleado, $this->id_empleado);
        }

        return $query;
    }

    public function getPrestamos($model, $todos, $pluck, $key_empleado = 'empleado_id', $query = null)
    {

        if ($query != null) {
            if ($todos) {
                if ($pluck) {
                    return $query == null ? null : $query->pluck('total_valor',  $key_empleado);
                } else {
                    return $query->get();
                }
            } else {
                $suma =  $query == null ? 0 : $query->pluck('total_valor',  $key_empleado);
                return $suma != 0 ? [$this->id_empleado] : 0;
            }
        } else {
            $query = $this->query($model, $todos, $key_empleado);
            if ($todos) {
                if ($query != null) {
                    $query->groupBy($key_empleado)
                        ->select($key_empleado, DB::raw('SUM(valor) as total_valor'));
                }
                if ($pluck) {
                    return $query == null ? null : $query->pluck('total_valor', $key_empleado);
                } else {
                    return $query->get();
                }
            } else {
                $query->select($key_empleado, DB::raw('SUM(valor) as total_valor'));
                $prestamo = $query->first();
                return $prestamo->total_valor != null  ?  $prestamo->total_valor : 0;
            }
        }
    }

    public function prestamosHipotecarios($todos = false, $pluck = false)
    {
        $mes_convertido = Carbon::createFromFormat('Y-m', $this->mes)->format('m-Y');
        $query = $this->query(PrestamoHipotecario::where('mes',  $mes_convertido), $todos);
        return $this->getPrestamos($query, $todos, $pluck);
    }

    public function prestamosQuirografarios($todos = false, $pluck = false)
    {
        $mes_convertido = Carbon::createFromFormat('Y-m', $this->mes)->format('m-Y');
        $query = $this->query(PrestamoQuirorafario::where('mes', $mes_convertido), $todos);
        return $this->getPrestamos($query, $todos, $pluck);
    }

    public function prestamosEmpresariales($todos = false, $pluck = false)
    {
        $query = PrestamoEmpresarial::where('estado', 'ACTIVO')
            ->whereRaw('DATE_FORMAT(plazos.fecha_vencimiento, "%Y-%m") <= ?', [$this->mes])
            ->join('plazo_prestamo_empresarial as plazos', 'prestamo_empresarial.id', '=', 'plazos.id_prestamo_empresarial');

        if ($todos) {
            $query->groupBy('prestamo_empresarial.id')
                ->select('solicitante', DB::raw('SUM(plazos.valor_a_pagar) as total_valor'));

            if ($pluck) {
                return $query->pluck('total_valor', 'solicitante');
            } else {
                return $query->get();
            }
        } else {
            list($anio, $mes) = explode('-', $this->mes);
            $prestamo = PrestamoEmpresarial::join('plazo_prestamo_empresarial', 'prestamo_empresarial.id', '=', 'plazo_prestamo_empresarial.id_prestamo_empresarial')
                ->where('prestamo_empresarial.solicitante', $this->id_empleado)
                ->whereYear('plazo_prestamo_empresarial.fecha_vencimiento', $anio)
                ->whereMonth('plazo_prestamo_empresarial.fecha_vencimiento', $mes)
                ->where('plazo_prestamo_empresarial.pago_couta', 0)
                ->selectRaw('SUM(plazo_prestamo_empresarial.valor_a_pagar) as total_valor')
                ->first();

            return $prestamo->total_valor != null ? $prestamo->total_valor : 0;
        }
    }
    public function pagarPrestamoEmpresarial()
    {
        list($anio, $mes) = explode('-', $this->mes);
        $prestamo = PlazoPrestamoEmpresarial::whereYear('fecha_vencimiento', $anio)
            ->whereMonth('fecha_vencimiento', $mes)
            ->where('pago_couta', 0)
            ->first();
        Log::channel('testing')->info('Log', ['prestamo pagado', $prestamo]);
        if ($prestamo != null) {
            $prestamo->valor_pagado = $prestamo->valor_a_pagar;
            $prestamo->valor_a_pagar = 0;
            $prestamo->fecha_pago = $prestamo->fecha_vencimiento;
            $prestamo->pago_couta = 1;
            $prestamo->save();
        }
    }
}
