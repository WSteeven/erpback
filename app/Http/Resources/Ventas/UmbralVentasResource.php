<?php

namespace App\Http\Resources\Ventas;

use Illuminate\Http\Resources\Json\JsonResource;

class UmbralVentasResource extends JsonResource
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
            'cantidad_ventas' => $this->cantidad_ventas,
            'vendedor' => $this->vendedor_id,
            'vendedor_id' => $this->vendedor_id,
            'vendedor_info'=>$this->vendedor !=null?$this->vendedor->empleado->nombres.' '.$this->vendedor->empleado->apellidos:'',
        ];
        return $modelo;
    }
}
