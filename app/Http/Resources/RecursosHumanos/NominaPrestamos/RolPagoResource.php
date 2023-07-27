<?php

namespace App\Http\Resources\RecursosHumanos\NominaPrestamos;

use App\Models\RecursosHumanos\NominaPrestamos\ExtensionCoverturaSalud;
use App\Models\RecursosHumanos\NominaPrestamos\PrestamoHipotecario;
use App\Models\RecursosHumanos\NominaPrestamos\PrestamoQuirorafario;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;

class RolPagoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $controller_method = $request->route()->getActionMethod();
        $modelo = [
            'id' => $this->id,
            'fecha' => $this->cambiar_fecha($this->created_at),
            'empleado' => $this->empleado_id,
            'empleado_info' => $this->empleado_info->nombres . ' ' . $this->empleado_info->apellidos,
            'cargo' => $this->empleado_info->cargo,
            'salario' => $this->empleado_info->salario,
            'dias' => $this->dias,
            'mes' => $this->mes,
            'sueldo' => $this->sueldo,
            'bonificacion' => $this->bonificacion,
            'bono_recurente' => $this->bono_recurente,
            'concepto_ingreso_info' => $this->ConceptoIngreso($this->ingreso_rol_pago),
            'descuento_general_info' => $this->Descuentos($this->egreso_rol_pago, 'DescuentosGenerales'),
            'descuento_ley_info' => $this->DescuentosLey($this->empleado_info, $this),
            'multa_info' => $this->Descuentos($this->egreso_rol_pago, 'Multa'),
            'decimo_tercero' =>$this->decimo_tercero,
            'decimo_cuarto' =>$this->decimo_cuarto,
            'total_ingreso' => $this->total_ingreso,
            'total_egreso' => $this->total_egreso,
            'total' => $this->total,
        ];
        return $modelo;
    }
    private function DescuentosLey($empleado,$rol_pago)
    {

        $descuentos = [
            'Aporte IESS' =>  number_format( $rol_pago->iess,2),
            'SUPA' => $empleado['supa'],
            'Extension de Cobertura de Salud' => number_format(ExtensionCoverturaSalud::where('empleado_id', $empleado->id)->where('mes',  $rol_pago->mes)->sum('aporte'),2),
            'Prestamo Hipotecario' => number_format(PrestamoHipotecario::where('empleado_id', $empleado->id)->where('mes', $rol_pago->mes)->sum('valor'),2),
            'Prestamo Quirorafario' => number_format(PrestamoQuirorafario::where('empleado_id', $empleado->id)->where('mes', $rol_pago->mes)->sum('valor'),2),
        ];
        $consulta = http_build_query($descuentos, '', ', ');
        $consulta = str_replace(['%3A', '%26'], [':', ','], $consulta);
        $consulta = str_replace('=', ': ', $consulta);
        return  str_replace('+', ' ', $consulta);
    }
    private function ConceptoIngreso($ingresos)
    {
        if ($ingresos->isEmpty()) {
            return null;
        }

        $ingresosArray = $ingresos->map(function ($ingreso) {
            $clave = $ingreso['concepto_ingreso_info']->nombre;
            $valor = $ingreso->monto;
            return $clave . ': ' . $valor;
        })->toArray();

        $ingresosString = implode(', ', $ingresosArray);

        return $ingresosString;
    }
    private function Descuentos($egresos, $tipo)
    {
        if ($egresos->isEmpty()) {
            return null;
        }
        $egresosArray = $egresos->filter(function ($egreso) use ($tipo) {
            $tipo_descuento = str_replace("App\\Models\\RecursosHumanos\\NominaPrestamos\\", "", $egreso['descuento_type']);
            return $tipo_descuento == $tipo;
        })->map(function ($egreso) {
            $clave = $egreso['descuento']->nombre;
            $valor = $egreso->monto;
            return $clave . ': ' . $valor;
        })->toArray();
        $egresosString = implode(', ', $egresosArray);
        return $egresosString;
    }
    private function cambiar_fecha($fecha)
    {
        $fecha_formateada = Carbon::parse($fecha)->format('d-m-Y');
        return $fecha_formateada;
    }
}
