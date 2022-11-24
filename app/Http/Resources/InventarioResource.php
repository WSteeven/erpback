<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;

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
        // Log::channel('testing')->info('Log', ['inventario resource', $request]);
        // return parent::toArray($request);
        $controller_method = $request->route()->getActionMethod();


        $modelo =[
            'id'=>$this->id,
            'producto'=>$this->detalle->producto->nombre,
            'detalle_id'=>$this->detalle->descripcion.' | '.$this->detalle->modelo->marca->nombre.' | '.$this->detalle->modelo->nombre.' | '.$this->detalle->serial,
            'cliente_id'=> $this->cliente->empresa->razon_social,
            'sucursal_id'=>$this->sucursal->lugar,
            'condicion'=> $this->condicion->nombre,
            'cantidad'=> $this->cantidad,
            'por_recibir'=> $this->por_recibir,
            'por_entregar'=> $this->por_entregar,
            // 'prestados'=>$this->prestados,
            'estado'=>$this->estado,
        ];
        if($controller_method=='show'){
            $modelo['producto']=$this->detalle->producto_id;
            $modelo['detalle_id']=$this->detalle_id;
            $modelo['sucursal_id']=$this->sucursal_id;
            $modelo['cliente_id']=$this->cliente_id;
            $modelo['condicion']=$this->condicion_id;
        }

        return $modelo;
    }
}
