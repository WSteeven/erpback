<?php

namespace App\Http\Resources\Ventas;

use Illuminate\Http\Resources\Json\JsonResource;

class EsquemaComisionResource extends JsonResource
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
            'mes_liquidacion' => $this->mes_liquidacion,
            'esquema_comision' => $this->esquema_comision,
            'tarifa_basica' => $this->tarifa_basica,
        ];
        return $modelo;    }
}
