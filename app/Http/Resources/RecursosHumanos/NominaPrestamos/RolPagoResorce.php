<?php

namespace App\Http\Resources\RecursosHumanos\NominaPrestamos;

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
            'empleado_info' => $this->empleado_info->nombres.' '.$this->empleado_info->apellidos,
            'salario' => $this->salario,
            'dias' => $this->dias,
            'sueldo' => $this->sueldo,
            'decimo_tercero' => $this->decimo_tercero,
            'decimo_cuarto' => $this->decimo_cuarto,
            'fondos_reserva' => $this->fondos_reserva,
            'alimentacion' => $this->alimentacion,
            'horas_extras' => $this->horas_extras,
            'total_ingreso' => $this->total_ingreso,
            'comisiones' => $this->comisiones,
            'iess' => $this->iess,
            'anticipo' => $this->anticipo,
            'prestamo_quirorafario' => $this->prestamo_quirorafario,
            'prestamo_hipotecario' => $this->prestamo_hipotecario,
            'extension_conyugal' => $this->extension_conyugal,
            'prestamo_empresarial' => $this->prestamo_empresarial,
            'sancion_pecuniaria' => $this->sancion_pecuniaria,
            'total_egreso' => $this->total_egreso,
            'total' => $this->total_ingreso - $this->total_egreso,
        ];
        return $modelo;
    }
   private function cambiar_fecha($fecha){
    $fecha_formateada = Carbon::parse( $fecha)->format('d-m-Y');
        return $fecha_formateada;
    }
}
