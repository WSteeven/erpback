<?php

namespace Src\App\RecursosHumanos\NominaPrestamos;

use App\Models\Empleado;
use App\Models\RecursosHumanos\NominaPrestamos\ExtensionCoverturaSalud;
use App\Models\RecursosHumanos\NominaPrestamos\RolPago;
use App\Models\RecursosHumanos\NominaPrestamos\RolPagoMes;
use App\Models\RecursosHumanos\NominaPrestamos\Rubros;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class NominaService
{
    private $mes;
    private $id_empleado;
    private Empleado $empleado;

    public function __construct($mes = null)
    {
        $this->mes = $mes == null ? Carbon::now() : $mes;
        $this->empleado = new Empleado();
    }
    public function setMes($mes)
    {
        $this->mes = $mes;
    }
    public function setEmpleado($id_empleado)
    {
        $this->id_empleado = $id_empleado;
        $this->empleado = Empleado::where('id', $this->id_empleado)->first();
    }
    public function permisoEmpleado($recupero = 0, $todos = false, $pluck = false)
    {
        $query = DB::table('permiso_empleados')
            ->whereRaw('DATE_FORMAT(fecha_hora_inicio, "%Y-%m") <= ?', [$this->mes])
            ->whereRaw('DATE_FORMAT(fecha_hora_fin, "%Y-%m") >= ?', [$this->mes])
            ->where('recupero', $recupero);

        if ($todos) {
            $query->groupBy('empleado_id')
                ->select('empleado_id', DB::raw('SUM(DATEDIFF(fecha_hora_fin, fecha_hora_inicio) + 1) as total_dias_permiso'));

            if ($pluck) {
                return $query->pluck('total_dias_permiso', 'empleado_id');
            } else {
                return $query->get();
            }
        } else {
            $query->where('empleado_id',  $this->id_empleado)
                ->groupBy('empleado_id')
                ->select('empleado_id', DB::raw('SUM(DATEDIFF(fecha_hora_fin, fecha_hora_inicio) + 1) as total_dias_permiso'));

            return $query->first() != null ? $query->first()->total_dias_permiso : 0;
        }
    }
    public function extensionesCoberturaSalud($todos = false, $pluck = false)
    {
        $query = ExtensionCoverturaSalud::where('mes', $this->mes);
        if ($todos) {
            $query->groupBy('empleado_id')
                ->select('empleado_id', DB::raw('SUM(aporte) as total_valor'));

            if ($pluck) {
                return $query->pluck('total_valor', 'empleado_id');
            } else {
                return $query->get();
            }
        } else {
            $extension_conyugal =  $query->where('empleado_id',  $this->id_empleado)
                ->groupBy('empleado_id')
                ->select('empleado_id', DB::raw('SUM(aporte) as total_valor'))->first();
            $suma =   $extension_conyugal == null ? 0 : $extension_conyugal->total_valor;
            return $suma != 0 ? [$this->id_empleado] : 0;
        }
    }
    public static function  obtenerValorRubro($rubroId)
    {
        $rubro = Rubros::find($rubroId);
        return $rubro != null ? $rubro->valor_rubro : 0;
    }

    public static function calcularSueldoBasico()
    {
        return NominaService::obtenerValorRubro(2);
    }

    public static function calcularPorcentajeIESS()
    {
        return NominaService::obtenerValorRubro(1) / 100;
    }

    public static function calcularPorcentajeAnticipo()
    {
        return NominaService::obtenerValorRubro(4) / 100;
    }
    public function calcularSueldo($dias = 30, $es_quincena = false, $porcentaje_anticipo = 0)
    {
        $porcentaje_anticipo = $porcentaje_anticipo / 100;
        $salario_diario = $this->empleado->salario / 30;
        if ($es_quincena) {
            $porcentaje_usar = $porcentaje_anticipo > 0 ? $porcentaje_anticipo : $this->calcularPorcentajeAnticipo();
            $sueldo = $salario_diario * 30 * $porcentaje_usar;
        } else {
            $dias_trabajados = $dias - $this->permisoEmpleado();
            $sueldo = $salario_diario * $dias_trabajados;
        }
        return $sueldo;
    }
    public function calcularAporteIESS()
    {
        $sueldo = $this->calcularSueldo(30);
        $iess = ($sueldo) * NominaService::calcularPorcentajeIESS();
        return $iess;
    }
    public function calcularDecimo($tipo, $dias)
    {
        switch ($tipo) {
            case 3:
                return ($this->empleado->salario / 360) * $dias;
                break;
            case 4:
                return (NominaService::calcularSueldoBasico() / 360) * $dias;
                break;
        }
    }
    public function calcularAnticipo()
    {
        $mes_rol_anterior = Carbon::createFromFormat('Y-m', $this->mes)->format('m-Y');
        $rol_usuario = RolPago::join('rol_pago_mes', 'rol_pago.rol_pago_id', '=', 'rol_pago_mes.id')
            ->where('rol_pago.empleado_id', $this->id_empleado)
            ->where('rol_pago_mes.mes', $mes_rol_anterior)
            ->where('rol_pago_mes.es_quincena', 1)
            ->select('rol_pago.*')
            ->first();
        return $rol_usuario != null ? $rol_usuario->total : 0;
    }
    public function calcularFondosReserva()
    {
        $fondosDeReserva = 0;
        // Obtén la fecha de ingreso del empleado y conviértela a un objeto Carbon
        $fechaIngreso = Carbon::parse($this->empleado->fecha_ingreso);
        // Obtén la fecha actual
        $hoy = Carbon::now();
        // Calcula la diferencia en días entre las dos fechas
        $diasTrabajados = $hoy->diffInDays($fechaIngreso);
        if ($diasTrabajados >= 366) {
            $fondosDeReserva = $this->empleado->salario * 0.0833; // 8.33% del sueldo
        }
        return $fondosDeReserva;
    }
}
