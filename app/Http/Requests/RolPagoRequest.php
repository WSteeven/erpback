<?php

namespace App\Http\Requests;

use App\Models\Empleado;
use App\Models\RecursosHumanos\NominaPrestamos\ExtensionCoverturaSalud;
use App\Models\RecursosHumanos\NominaPrestamos\PrestamoEmpresarial;
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
        $bono_recurente = $this->bono_recurente;
        $bonificacion = $this->bonificacion;
        $totalIngresos = $totalIngresos = !empty($this->ingresos)
            ? array_reduce($this->ingresos, function ($acumulado, $ingreso) {
                return $acumulado + (float) $ingreso['monto'];
            }, 0)
            : 0;
        $ingresos = $sueldo + $decimo_tercero + $decimo_cuarto + $fondos_reserva + $bonificacion + $bono_recurente + $totalIngresos;
        $iess = ($sueldo + $horas_extras + $comision) * $porcentaje_iess;
        $anticipo = $sueldo *  $porcentaje_anticipo;
        $prestamo_quirorafario = PrestamoQuirorafario::where('empleado_id', $empleado->id)->where('mes', $this->mes)->sum('valor');
        $prestamo_hipotecario = PrestamoHipotecario::where('empleado_id', $empleado->id)->where('mes', $this->mes)->sum('valor');
        $extension_conyugal = ExtensionCoverturaSalud::where('empleado_id', $empleado->id)->where('mes', $this->mes)->sum('aporte');
        $prestamo_empresarial = 0;
        $prestamo_empresarial = PrestamoEmpresarial::where('estado', 'ACTIVO')
            ->whereRaw('DATE_FORMAT(fecha, "%Y-%m") <= ?', [$this->mes])
            ->sum('monto');
        $multas = 0; /*array_reduce($this->multas, function ($acumulado, $multa) {
            return $acumulado + (float) $multa['monto'];
        }, 0);*/
        $totalEgresos = $totalIngresos = !empty($this->egresos)
            ? array_reduce($this->multas, function ($acumulado, $egreso) {
                return $acumulado + (float) $egreso['monto'];
            }, 0) : 0;
        $egreso = $iess + $anticipo + $prestamo_quirorafario + $prestamo_hipotecario + $extension_conyugal + $prestamo_empresarial + $multas + $totalEgresos;
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
            'total_egreso' =>  $egreso,
            'total' =>  $total,
        ]);
    }
}
