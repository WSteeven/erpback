<?php

namespace App\Http\Resources\RecursosHumanos\NominaPrestamos;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

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
            'dias' => $this->dias,
            'sueldo' => $this->sueldo,
            'ingresos' => $this->Ingresos($this->ingreso_rol_pago),
            'total_ingreso' => $this->total_ingreso,
            'total_egreso' => $this->total_egreso,
            'total' => $this->total,
        ];
        return $modelo;
    }
    private function Ingresos($ingresos)
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
    private function cambiar_fecha($fecha)
    {
        $fecha_formateada = Carbon::parse($fecha)->format('d-m-Y');
        return $fecha_formateada;
    }
}
