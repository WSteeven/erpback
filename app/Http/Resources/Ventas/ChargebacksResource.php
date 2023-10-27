<?php

namespace App\Http\Resources\Ventas;

use Illuminate\Http\Resources\Json\JsonResource;

class ChargebacksResource extends JsonResource
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
            'venta_id' => $this->venta_id,
            'venta' => $this->venta_id,
            'venta_info' => $this->venta->orden_interna,
            'fecha' => $this->fecha,
            'valor' => $this->valor,
            'id_tipo_chargeback' => $this->id_tipo_chargeback,
            'tipo_chargeback' => $this->id_tipo_chargeback,
            'tipo_chargeback_info' => $this->tipo_chargeback->nombre,
            'porcentaje' => $this->porcentaje
        ];
        return $modelo;
    }
}
