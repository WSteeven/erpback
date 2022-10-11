<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ControlStockResource extends JsonResource
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
            'producto'=>$this->detalle->producto->nombre,
            'detalle_id'=>$this->detalle->descripcion,
            'cliente_id'=>$this->cliente->empresa->razon_social,
            'sucursal_id'=>$this->sucursal->lugar,
            'minimo'=>$this->minimo,
            'reorden'=>$this->reorden,
            'estado'=>$this->estado,
        ];

        if($controller_method=='show'){
            $modelo['sucursal_id']=$this->sucursal_id;
            $modelo['detalle_id']=$this->detalle_id;
            $modelo['cliente_id']=$this->cliente_id;
        }

        return $modelo;
    }
}
