<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductoEnPerchaResource extends JsonResource
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
            'id'=>$this->id,
            'ubicacion'=>$this->ubicacion->codigo,
            'inventario'=>$this->inventario->detalle->descripcion,
            'stock'=>$this->stock,
        ];
        if($controller_method=='show'){
            $modelo['ubicacion_id']=$this->ubicacion_id;
            $modelo['inventario_id']=$this->ubicacion_id;
        }

        return $modelo;
    }
}
