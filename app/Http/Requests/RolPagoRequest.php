<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class RolPagoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'fecha' => 'required',
            'empleado' => 'required|number',
            'salario' => 'required|string',
            'dias' => 'required|number',
            'sueldo' => 'required|number',
            'decimo_tercero' => 'required|number',
            'decimo_cuarto' => 'required|number',
            'fondos_reserva' => 'required|number',
            'alimentacion' => 'required|number',
            'horas_extras' => 'required|number',
            'total_ingreso' => 'required|number',
            'comisiones' => 'required|number',
            'iess' => 'required|number',
            'anticipo' => 'required|number',
            'prestamo_quirorafario' => 'required|number',
            'prestamo_hipotecario' => 'required|number',
            'extension_conyugal' => 'required|number',
            'prestamo_empresarial' => 'required|number',
            'sancion_pecuniaria' => 'required|number',
            'total_egreso' => 'required|number',
        ];
    }
    protected function prepareForValidation()
    {
        $sueldo_basico = 450;
        $horas_extras = 0;
        $comision =0;
        $sueldo = ($this->salario/30)*$this->dias;
        $decimo_tercero = ($this->salario/360)*$this->dias;
        $decimo_cuarto = ($sueldo_basico/360)*$this->dias;
        $fondos_reserva = $sueldo*8.33;
        $ingresos = $sueldo+$decimo_tercero+$decimo_cuarto+$fondos_reserva+$this->alimentacion+$horas_extras;
        $iess = ($sueldo+$horas_extras+$comision)*0.945;
        $anticipo = $sueldo *0.40;
        $prestamo_quirorafario = 0;
        $prestamo_hipotecario = 0;
        $extension_conyugal = 0;
        $prestamo_empresarial = 0;
        $prestamo_empresarial = 0;//Prestamo::where('empleado_id',$this->empleado)->where('estado','activo')->where('tipo','empresarial')->sum('cuota');
        $sancion_pecuniaria = 0;//Sancion::where('empleado_id',$this->empleado)->where('estado','activo')->sum('monto');
        $descuento_herramientas = 0;//Herramienta::where('empleado_id',$this->empleado)->where('estado','activo')->sum('monto');
        $egreso = $iess+$anticipo+$prestamo_quirorafario+$prestamo_hipotecario+$extension_conyugal+$prestamo_empresarial+$sancion_pecuniaria+$descuento_herramientas;
        $total = abs($ingresos)-$egreso;
        $this->merge([
            'fecha' => Carbon::now(),
            'sueldo' =>  $sueldo,
            'horas_extras' =>  $horas_extras,
            'comisiones' =>  $comision,
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
            'sancion_pecuniaria' =>  $sancion_pecuniaria,
            'total_egreso' =>  $egreso,
            'total' =>  $total,
        ]);
    }
}
