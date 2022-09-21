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
            $modelo['detalle']=$this->detalle_id;
            $modelo['sucursal']=$this->sucursal_id;
            $modelo['cliente']=$this->cliente_id;
            $modelo['condicion']=$this->condicion_id;
        }

        return $modelo;
    }
}
