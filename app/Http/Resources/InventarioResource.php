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


        $modelo =$this->detalle?[
            'id'=>$this->id,
            'producto'=>$this->detalle?->producto->nombre,
            'detalle'=>$this->detalle_id,
            'detalle_id'=>$this->detalle->descripcion.' | '.$this->detalle->modelo->marca->nombre.' | '.$this->detalle->modelo->nombre.' | '.$this->detalle->serial,
            'descripcion'=>$this->detalle->descripcion,
            'categoria'=>$this->detalle->producto->categoria->nombre,
            'cliente'=>$this->cliente_id,
            'cliente_id'=> $this->cliente->empresa->razon_social,
            'serial'=>$this->detalle->serial,
            'sucursal'=>$this->sucursal_id,
            'sucursal_id'=>$this->sucursal?->lugar,
            'condiciones'=> $this->condicion->nombre,
            'cantidad'=> $this->cantidad,
            'por_recibir'=> $this->por_recibir,
            'por_entregar'=> $this->por_entregar,
            // 'prestados'=>$this->prestados,
            'estado'=>$this->estado,
        ]:[];
        if($controller_method=='show'){
            $modelo['producto']=$this->detalle->producto_id;
            $modelo['detalle_id']=$this->detalle_id;
            $modelo['sucursal_id']=$this->sucursal_id;
            $modelo['cliente_id']=$this->cliente_id;
            $modelo['condiciones']=$this->condicion_id;
        }

        return $modelo;
    }
}
