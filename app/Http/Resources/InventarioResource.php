<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class InventarioResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        //return parent::toArray($request);
        $controller_method = $request->route()->getActionMethod();

        $modelo =[
            'id'=>$this->id,
            'producto'=>$this->detalle->producto->nombre,
            'detalle'=>$this->detalle->descripcion,
            'cliente'=> $this->cliente->empresa->razon_social,
            'sucursal'=>$this->sucursal->lugar,
            'condicion'=> $this->condicion->nombre,
            'cantidad'=> $this->cantidad,
            'prestados'=>$this->prestados,
            'estado'=>$this->estado,
        ];
        if($controller_method=='show'){
            $modelo['producto_id']=$this->detalle->producto_id;
            $modelo['detalle_id']=$this->detalle_id;
            $modelo['sucursal_id']=$this->sucursal_id;
            $modelo['cliente_id']=$this->cliente_id;
            $modelo['condicion_id']=$this->condicion_id;
        }

        return $modelo;
    }
}
