<?php

namespace App\Http\Resources\RecursosHumanos\NominaPrestamos;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class PrestamoEmpresarialResource extends JsonResource
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
            'solicitante' => $this->solicitante,
            'solicitante_info' => $this->empleado_info->nombres . ' ' . $this->empleado_info->apellidos,
            'fecha' =>  $this->cambiar_fecha($this->fecha),
            'monto' =>  $this->monto,
            'utilidad' => $this->utilidad,
            'valor_utilidad' => $this->valor_utilidad,
            'forma_pago' => $this->id_forma_pago,
            'forma_pago_info' => $this->forma_pago_info->nombre,
            'plazo' => $this->plazo,
            'plazos' => $this->plazo_prestamo_empresarial_info,
            'estado' => $this->estado,


        ];
        return $modelo;
    }
    private function cambiar_fecha($fecha)
    {
        $fecha_formateada = Carbon::parse($fecha)->format('d-m-Y');
        return $fecha_formateada;
    }
}
