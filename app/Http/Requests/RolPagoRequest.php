<?php

namespace App\Http\Requests;

use App\Models\Empleado;
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
            'fecha' => 'required',
            'empleado' => 'required',
            'salario' => 'required|string',
            'dias' => 'required',
            'sueldo' => 'required',
            'decimo_tercero' => 'required',
            'decimo_cuarto' => 'required',
            'fondos_reserva' => 'required',
            'alimentacion' => 'required',
            'horas_extras' => 'required',
            'total_ingreso' => 'required',
            'comisiones' => 'required',
            'iess' => 'required',
            'anticipo' => 'required',
            'prestamo_quirorafario' => 'required',
            'prestamo_hipotecario' => 'required',
            'extension_conyugal' => 'required',
            'prestamo_empresarial' => 'required',
            'sancion_pecuniaria' => 'required',
            'total_egreso' => 'required',
        ];
    }
    protected function prepareForValidation()
    {
        $empleado =Empleado::find($this->empleado);
        $fechaInicio = Carbon::parse($empleado->fecha_ingreso);
        $fechaFin = $fechaInicio->copy()->addMonths(13);
        $sueldo_basico = 450;
        $horas_extras = 0;
        $comision =0;
        $sueldo = ($this->salario/30)*$this->dias;
        $decimo_tercero = ($this->salario/360)*$this->dias;
        $decimo_cuarto = ($sueldo_basico/360)*$this->dias;
        $fondos_reserva = 0;
        if ($fechaFin->diffInMonths($fechaInicio) == 13) {
            // Han pasado 13 meses
            $fondos_reserva = $sueldo*8.33;
        }

        $ingresos = $sueldo+$decimo_tercero+$decimo_cuarto+$fondos_reserva+$this->alimentacion+$horas_extras;
        $iess = ($sueldo+$horas_extras+$comision)*0.0945;
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
