<?php

namespace Src\App\RecursosHumanos\NominaPrestamos;

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
                $prestamo = $query->first();
                return $prestamo != null  ?  $prestamo->valor : 0;
            }
        }
    }

    public function prestamosHipotecarios($todos = false, $pluck = false)
    {
        $query = $this->query(PrestamoHipotecario::where('mes', $this->mes), $todos);
        return $this->getPrestamos($query, $todos, $pluck);
    }

    public function prestamosQuirografarios($todos = false, $pluck = false)
    {
        $query = $this->query(PrestamoQuirorafario::where('mes', $this->mes), $todos);
        return $this->getPrestamos($query, $todos, $pluck);
    }

    public function prestamosEmpresariales( $todos = false, $pluck = false)
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
        $query->where('solicitante', $this->id_empleado)
            ->groupBy('solicitante')
            ->select('solicitante', DB::raw('SUM(plazos.valor_a_pagar) as total_valor'));
        $prestamo =    $query->first();
        return $prestamo != null ?$prestamo->total_valor:0;
    }
}
}
