<?php

namespace App\Http\Resources\RecursosHumanos\NominaPrestamos;

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
            'solicitante' => $this->empleado_id,
            'solicitante_info' => $this->empleado_info->nombres.' '.$this->empleado_info->apellidos,
            'fecha' =>  $this->cambiar_fecha($this->fecha),
            'monto' =>  $this->monto,
            'utilidad' => $this->utilidad ,
            'valor_utilidad' => $this->valor_utilidad,
            'forma_pago' => $this->id_forma_pago,
            'forma_pago_info' => $this->forma_pago_info->nombre,
            'plazo' => $this->plazo,
            'estado' => $this->estado_permiso_id,


        ];
        return $modelo;
    }
}
