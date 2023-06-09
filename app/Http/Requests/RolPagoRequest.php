<?php

namespace App\Http\Requests;

use App\Models\Empleado;
use App\Models\RecursosHumanos\NominaPrestamos\ExtensionCoverturaSalud;
use App\Models\RecursosHumanos\NominaPrestamos\PrestamoHipotecario;
use App\Models\RecursosHumanos\NominaPrestamos\PrestamoQuirorafario;
use App\Models\RecursosHumanos\NominaPrestamos\Rubros;
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
            'empleado' => 'required',
            'mes' => 'required',
            'dias' => 'required',
            'ingresos' => 'required',
            'decimo_tercero' =>  'required',
            'decimo_cuarto' => 'required',
            'total_ingreso'=> 'required',
            'egresos' => 'required',
            'iess' =>  'required',
            'total_egreso' => 'required',
            'total' => 'required'
        ];
    }
    protected function prepareForValidation()
    {
        $empleado = Empleado::find($this->empleado);
        $fechaInicio = Carbon::parse($empleado->fecha_ingreso);
        $fechaFin = $fechaInicio->copy()->addMonths(13);
        $salario = $empleado->salario;
        $sueldo_basico =  Rubros::find(2) != null ? Rubros::find(2)->valor_rubro : 0;
        $porcentaje_iess = Rubros::find(1) != null ? Rubros::find(1)->valor_rubro / 100 : 0;
        $porcentaje_anticipo = Rubros::find(4) != null ? Rubros::find(4)->valor_rubro / 100 : 0;
        $horas_extras = $this->horas_extras;
        $comision = $this->comision;
        $sueldo = ($salario / 30) * $this->dias;
        $decimo_tercero = ($salario / 360) * $this->dias;
        $decimo_cuarto = ($sueldo_basico / 360) * $this->dias;
        $fondos_reserva = 0;
        $ingresos = $sueldo + $decimo_tercero + $decimo_cuarto + $fondos_reserva + $this->alimentacion + $horas_extras;
        $iess = ($sueldo + $horas_extras + $comision) * $porcentaje_iess;
        $anticipo = $sueldo *  $porcentaje_anticipo;
        $prestamo_quirorafario = PrestamoQuirorafario::where('empleado_id', $empleado->id)->where('mes', $this->mes)->sum('valor');
        $prestamo_hipotecario = PrestamoHipotecario::where('empleado_id', $empleado->id)->where('mes', $this->mes)->sum('valor');
        $extension_conyugal = ExtensionCoverturaSalud::where('empleado_id', $empleado->id)->where('mes',$this->mes)->sum('aporte');
        $prestamo_empresarial = 0;
        $prestamo_empresarial = 0; //Prestamo::where('empleado_id',$this->empleado)->where('estado','activo')->where('tipo','empresarial')->sum('cuota');
        $sancion_pecuniaria = 0; //Sancion::where('empleado_id',$this->empleado)->where('estado','activo')->sum('monto');
        $descuento_herramientas = 0; //Herramienta::where('empleado_id',$this->empleado)->where('estado','activo')->sum('monto');
        $egreso = $iess + $anticipo + $prestamo_quirorafario + $prestamo_hipotecario + $extension_conyugal + $prestamo_empresarial + $sancion_pecuniaria + $descuento_herramientas;
        $total = abs($ingresos) - $egreso;
        $this->merge([
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
