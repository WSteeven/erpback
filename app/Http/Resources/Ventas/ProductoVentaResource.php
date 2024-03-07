<?php

namespace App\Http\Resources\Ventas;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductoVentaResource extends JsonResource
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
            'nombre' => $this->nombre,
            'bundle' => $this->bundle_id,
            'precio' => $this->precio,
            'plan' => $this->plan_id,
            'plan_info'=> $this->plan->nombre,
            'activo'=> $this->activo,
        ];
        return $modelo;
    }
}
