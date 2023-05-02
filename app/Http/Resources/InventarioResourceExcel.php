<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class InventarioResourceExcel extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'=>$this->id,
            'producto'=>$this->detalle->producto->nombre,
            'descripcion'=>$this->detalle->descripcion,
            'categoria'=>$this->detalle->producto->categoria->nombre,
            'cliente'=> $this->cliente->empresa->razon_social,
            'serial'=>$this->detalle->serial,
            'sucursal'=>$this->sucursal->lugar,
            'condiciones'=> $this->condicion->nombre,
            'por_recibir'=> $this->por_recibir,
            'cantidad'=> $this->cantidad,
            'por_entregar'=> $this->por_entregar,
        ];
    }
}
