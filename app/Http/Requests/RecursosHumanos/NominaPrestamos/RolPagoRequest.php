<?php

namespace App\Http\Requests\RecursosHumanos\NominaPrestamos;

use App\Models\Empleado;
use App\Models\RecursosHumanos\NominaPrestamos\RolPago;
use App\Models\RecursosHumanos\NominaPrestamos\RolPagoMes;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;
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
            'salario' => 'required',
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
            'medio_tiempo' => 'nullable',
            'es_vendedor_medio_tiempo' => 'nullable',
            'egresos' => 'nullable',
            'iess' =>  'required',
            'extension_conyugal' => 'required',
            'fondos_reserva' => 'nullable',
            'total_egreso' => 'required',
            'total' => 'required',


        ];
    }
    protected function prepareForValidation()
    {
        $mes = Carbon::createFromFormat('m-Y', $this->mes)->format('Y-m');
        $nominaService = new NominaService($mes);
        $prestamoService = new PrestamoService($mes);
        $nominaService->setEmpleado($this->empleado);
        $prestamoService->setEmpleado($this->empleado);
        $rol = RolPagoMes::where('id', $this->rol_pago_id)->first();
        $nominaService->setRolPago($rol);
        $nominaService->setVendedorMedioTiempo($this->es_vendedor_medio_tiempo);
        $dias =  $this->dias;
        $sueldo = $nominaService->calcularSueldo($dias, $rol->es_quincena, $this->sueldo);
        $salario = $nominaService->calcularSalario();
        $decimo_tercero = $rol->es_quincena ? 0 : $nominaService->calcularDecimo(3, $this->dias);
        $decimo_cuarto = $rol->es_quincena ? 0 : $nominaService->calcularDecimo(4, $this->dias);
        $fondos_reserva = $rol->es_quincena ? 0 : $nominaService->calcularFondosReserva($this->dias);
        $bono_recurente =  $rol->es_quincena ? 0 : $this->bono_recurente;
        $bonificacion =  $rol->es_quincena ? 0 : $this->bonificacion;
        $totalIngresos =  $rol->es_quincena ? 0 : $totalIngresos = !empty($this->ingresos)
            ? array_reduce($this->ingresos, function ($acumulado, $ingreso) {
                return $acumulado + (float) $ingreso['monto'];
            }, 0)
            : 0;
        $ingresos = $rol->es_quincena ? $sueldo : $sueldo + $decimo_tercero + $decimo_cuarto + $fondos_reserva + $bonificacion + $bono_recurente + $totalIngresos;
        $iess =  $rol->es_quincena ? 0 : $nominaService->calcularAporteIESS($dias);
        $anticipo = $rol->es_quincena ? 0 : $nominaService->calcularAnticipo();
        $prestamo_quirorafario =   $rol->es_quincena ? 0 : $prestamoService->prestamosQuirografarios();
        $prestamo_hipotecario =  $rol->es_quincena ? 0 : $prestamoService->prestamosHipotecarios();
        $extension_conyugal =  $rol->es_quincena ? 0 : $nominaService->extensionesCoberturaSalud();
        $prestamo_empresarial = $rol->es_quincena ? 0 : $prestamoService->prestamosEmpresariales();
        $supa =  $rol->es_quincena ? 0 : $nominaService->calcularSupa();
        $totalEgresos = $totalEgresos = !empty($this->egresos)
            ? array_reduce($this->egresos, function ($acumulado, $egreso) {
                return $acumulado + (float) $egreso['monto'];
            }, 0) : 0;
        $egreso = $rol->es_quincena ? 0 : $iess + $anticipo + $prestamo_quirorafario + $prestamo_hipotecario + $extension_conyugal + $prestamo_empresarial + $totalEgresos + $supa;
        $total = abs($ingresos) - $egreso;
        if ($this->es_vendedor_medio_tiempo ==! null) {
            $this->merge([
                'es_vendedor_medio_tiempo' => $this->es_vendedor_medio_tiempo,
            ]);
        }
        $this->merge([
            'dias' => $dias,
            'salario' => $salario,
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
