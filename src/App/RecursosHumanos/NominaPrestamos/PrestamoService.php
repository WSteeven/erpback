<?php

namespace Src\App\RecursosHumanos\NominaPrestamos;

use App\Models\Empleado;
use App\Models\RecursosHumanos\NominaPrestamos\PlazoPrestamoEmpresarial;
use App\Models\RecursosHumanos\NominaPrestamos\PrestamoEmpresarial;
use App\Models\RecursosHumanos\NominaPrestamos\PrestamoHipotecario;
use App\Models\RecursosHumanos\NominaPrestamos\PrestamoQuirografario;
use App\Models\RecursosHumanos\NominaPrestamos\RolPagoMes;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

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

        $this->mes = $mes;
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
                    return $query->pluck('total_valor', $key_empleado);
                } else {
                    return $query->get();
                }
            } else {
                $suma = $query->pluck('total_valor', $key_empleado);
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
                    return null;
                } else {
                    return $query->get();
                }
            } else {
                $query->select($key_empleado, DB::raw('SUM(valor) as total_valor'));
                $prestamo = $query->first();
                return $prestamo->total_valor != null ? $prestamo->total_valor : 0;
            }
        }
    }

    public function prestamosHipotecarios($todos = false, $pluck = false)
    {
        $mes_convertido = Carbon::createFromFormat('Y-m', $this->mes)->format('m-Y');
        $query = $this->query(PrestamoHipotecario::where('mes', $mes_convertido), $todos);
        return $this->getPrestamos($query, $todos, $pluck);
    }

    public function prestamosQuirografarios($todos = false, $pluck = false)
    {
        $mes_convertido = Carbon::createFromFormat('Y-m', $this->mes)->format('m-Y');
        $query = $this->query(PrestamoQuirografario::where('mes', $mes_convertido), $todos);
        return $this->getPrestamos($query, $todos, $pluck);
    }

    /**
     * Obtiene la suma de valores pendientes de pago para el empleado actual en el mes y año configurados.
     * @return int|mixed
     */
    protected function consultarPrestamoEmpleadoActual()
    {
        [$anio, $mes] = explode('-', $this->mes);

        return PlazoPrestamoEmpresarial::join('prestamo_empresarial', 'plazo_prestamo_empresarial.id_prestamo_empresarial', '=', 'prestamo_empresarial.id')
            ->where('prestamo_empresarial.solicitante', $this->id_empleado)
            ->whereYear('fecha_vencimiento', $anio)
            ->whereMonth('fecha_vencimiento', $mes)
            ->where('pago_cuota', 0)
            ->where('plazo_prestamo_empresarial.estado', true)
            ->sum('valor_a_pagar') ?? 0;
    }

    /**
     * Obtiene la suma de valores pendientes de pago para todos los empleados en el mes configurado.
     * @param bool $pluck
     * @return PrestamoEmpresarial[]|Builder[]|Collection|\Illuminate\Database\Query\Builder[]|\Illuminate\Support\Collection
     */
    protected function consultarPrestamosDeTodos(bool $pluck)
    {
        $query = PrestamoEmpresarial::where('estado', 'ACTIVO')
            ->join('plazo_prestamo_empresarial as plazos', 'prestamo_empresarial.id', '=', 'plazos.id_prestamo_empresarial')
            ->whereRaw('DATE_FORMAT(plazos.fecha_vencimiento, "%Y-%m") <= ?', [$this->mes])
            ->groupBy('prestamo_empresarial.id')
            ->select(['solicitante', DB::raw('SUM(plazos.valor_a_pagar) as total_valor')]);

        return $pluck
            ? $query->pluck('total_valor', 'solicitante')
            : $query->get();
    }


    /**
     * Consulta préstamos empresariales, ya sea para un empleado específico o para todos.
     * @param bool $todos
     * @param bool $pluck
     * @return PrestamoEmpresarial[]|Builder[]|Collection|\Illuminate\Database\Query\Builder[]|\Illuminate\Support\Collection|int|mixed
     */
    public function prestamosEmpresariales(bool $todos = false, bool $pluck = false)
    {
        return $todos
            ? $this->consultarPrestamosDeTodos($pluck)
            : $this->consultarPrestamoEmpleadoActual();
    }

    /*public function prestamosEmpresarialesOld($todos = false, $pluck = false)
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
            $prestamo = PlazoPrestamoEmpresarial::
            join('prestamo_empresarial',  'plazo_prestamo_empresarial.id_prestamo_empresarial', '=','prestamo_empresarial.id')
            ->where('prestamo_empresarial.solicitante', $this->id_empleado)
            ->whereYear('fecha_vencimiento', $anio)
            ->whereMonth('fecha_vencimiento', $mes)
            ->where('pago_couta', 0)
           -> where('plazo_prestamo_empresarial.estado', true)
            ->sum('valor_a_pagar');
            return $prestamo != null ? $prestamo : 0;
        }
    }**/

    /**
     * Realiza el pago automático de cuotas de préstamos empresariales desde un rol de pago, validando montos y actualizando estados.
     * @throws Exception
     */
    public function pagarPrestamoEmpresarialDesdeRol(RolPagoMes $rolPagoMes)
    {
        foreach ($rolPagoMes->rolesPagos as $rol) {
            if ($rol->prestamo_empresarial > 0) { // si el rol tiene valor de prestamo empresarial mayor a cero se paga
                [$anio, $mes] = explode('-', $this->mes);
                // buscamos un prestamo para ese empleado
                $prestamo = PrestamoEmpresarial::where('solicitante', $rol->empleado_id)->where('estado', PrestamoEmpresarial::ACTIVO)->first();
                // Buscar cuotas pendientes del empleado en ese mes
                $cuotaActual = $prestamo->plazos()->where('pago_cuota', false)->where('estado', true)
                    ->whereYear('fecha_vencimiento', $anio)
                    ->whereMonth('fecha_vencimiento', $mes)->first();

                if ($cuotaActual) {
                    //sacamos el valor de la cuota
                    if ($cuotaActual->valor_a_pagar < $rol->prestamo_empresarial) {
                        $empleado = Empleado::find($rol->empleado_id);
                        $nombre_empleado = Empleado::extraerNombresApellidos($empleado);
                        throw new Exception("Estas intentando pagar mayor cantidad al valor de la cuota de este mes: $cuotaActual->valor_a_pagar, para el empleado $nombre_empleado, por favor realiza el pago manualmente en la sección préstamos empresariales");
                    }
                    $cuotaActual->valor_pagado += $rol->prestamo_empresarial;
                    $cuotaActual->valor_a_pagar -= $rol->prestamo_empresarial;
                    if ($cuotaActual->valor_a_pagar == 0 || $cuotaActual->valor_cuota == $cuotaActual->valor_pagado) {// significa que se ha pagado la letra en su totalidad
                        $cuotaActual->pago_cuota = true;
                        $cuotaActual->comentario = "Pago realizado automaticamente por rol de pagos de $rol->mes";
                    }
                    $cuotaActual->save();
                }
            }
        }
    }

}
