<?php

namespace App\Http\Requests\RecursosHumanos\NominaPrestamos;

use App\Models\Empleado;
use App\Models\RecursosHumanos\NominaPrestamos\RolPagoMes;
use App\Models\RecursosHumanos\NominaPrestamos\Rubros;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Src\App\RecursosHumanos\NominaPrestamos\NominaService;
use Src\App\RecursosHumanos\NominaPrestamos\PrestamoService;

class RolPagoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'rol_pago_id' => 'required',
            'empleado' => 'required',
            'mes' => 'required',
            'dias' => 'required',
            'sueldo' => 'required',
            'anticipo' => 'required',
            'ingresos' => 'nullable',
            'decimo_tercero' =>  'required',
            'decimo_cuarto' => 'required',
            'total_ingreso' => 'required',
            'bonificacion' => 'nullable',
            'bono_recurente' => 'nullable',
            'prestamo_quirorafario' => 'required',
            'prestamo_hipotecario' => 'required',
            'prestamo_empresarial' => 'required',
            'egresos' => 'nullable',
            'iess' =>  'required',
            'extension_conyugal' => 'required',
            'total_egreso' => 'required',
            'total' => 'required'
        ];
    }
    protected function prepareForValidation()
    {
        $empleado = Empleado::find($this->empleado);
        $nominaService = new NominaService($this->mes);
        $prestamoService = new PrestamoService($this->mes);
        $nominaService->setEmpleado($this->empleado);
        $prestamoService->setEmpleado($this->empleado);
        /*$fechaInicio = Carbon::parse($empleado->fecha_ingreso);
        $fechaFin = $fechaInicio->copy()->addMonths(13);*/
        $salario = $empleado->salario;
        $sueldo_basico =  Rubros::find(2) != null ? Rubros::find(2)->valor_rubro : 0;
        $porcentaje_iess = Rubros::find(1) != null ? Rubros::find(1)->valor_rubro / 100 : 0;
        $porcentaje_anticipo = Rubros::find(4) != null ? Rubros::find(4)->valor_rubro / 100 : 0;
        $dias_permiso_sin_recuperar = $this->dias_permiso_sin_recuperar;
        $rol = RolPagoMes::where('id', $this->rol_pago_id)->first();
        $decimo_tercero = 0;
        $decimo_cuarto = 0;
        $fondos_reserva = 0;
        $iess = 0;
        $anticipo = 0;
        $prestamo_quirorafario =  0;
        $prestamo_hipotecario =  0;
        $extension_conyugal =  0;
        $prestamo_empresarial =  0;
        $ingresos = 0;
        $egreso = 0;
        $sueldo = 0;
        $bono_recurente = 0;
        $bonificacion = 0;
        $dias = $this->dias;
        $totalIngresos = $totalIngresos = !empty($this->ingresos)
            ? array_reduce($this->ingresos, function ($acumulado, $ingreso) {
                return $acumulado + (float) $ingreso['monto'];
            }, 0)
            : 0;
        if ($rol->es_quincena) {
            $dias = 15;
            $sueldo = ($salario / 30) * ($dias - $dias_permiso_sin_recuperar);
            $ingresos = $sueldo + $decimo_tercero + $decimo_cuarto + $fondos_reserva + $bonificacion + $bono_recurente + $totalIngresos;
        } else {
            $sueldo = ($salario / 30) * ($dias - $dias_permiso_sin_recuperar);
            $decimo_tercero = ($salario / 360) * $dias;
            $decimo_cuarto = ($sueldo_basico / 360) * $dias;
            $bono_recurente = $this->bono_recurente;
            $bonificacion = $this->bonificacion;
            $iess = ($sueldo) * $porcentaje_iess;
            $anticipo = $sueldo *  $porcentaje_anticipo;

            $ingresos = $sueldo + $decimo_tercero + $decimo_cuarto + $fondos_reserva + $bonificacion + $bono_recurente + $totalIngresos;
            $prestamo_quirorafario =  $prestamoService->prestamosQuirografarios();
            $prestamo_hipotecario = $prestamoService->prestamosHipotecarios();
            $extension_conyugal = $nominaService->extensionesCoberturaSalud();
            $prestamo_empresarial =$prestamoService->prestamosEmpresariales();
            $totalEgresos = $totalIngresos = !empty($this->egresos)
                ? array_reduce($this->egresos, function ($acumulado, $egreso) {
                    return $acumulado + (float) $egreso['monto'];
                }, 0) : 0;
            $egreso = $iess + $anticipo + $prestamo_quirorafario + $prestamo_hipotecario + $extension_conyugal + $prestamo_empresarial + $totalEgresos;
        }

        $total = abs($ingresos) - $egreso;
        $this->merge([
            'sueldo' =>  $sueldo,
            'decimo_tercero' =>  $decimo_tercero,
            'decimo_cuarto' =>  $decimo_cuarto,
            'fondos_reserva' =>  $fondos_reserva,
            'total_ingreso' =>  $ingresos,
            'iess' =>  $iess,
            'anticipo' =>  $anticipo,
            'prestamo_quirorafario' =>  $prestamo_quirorafario,
            'prestamo_hipotecario' =>  $prestamo_hipotecario,
            'extension_conyugal' =>  $extension_conyugal,
            'prestamo_empresarial' =>  $prestamo_empresarial,
            'total_egreso' =>  $egreso,
            'total' =>  $total,
        ]);
    }
}
