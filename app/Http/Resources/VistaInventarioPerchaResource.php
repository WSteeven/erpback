<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VistaInventarioPerchaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        $modelo =[
            'id'=>$this->id,
            'producto'=>$this->detalle->producto->nombre,
            'detalle_id'=>$this->detalle->descripcion.' | '.$this->detalle->modelo->marca->nombre.' | '.$this->detalle->modelo->nombre.' | '.$this->detalle->serial,
            'cliente_id'=> $this->cliente->empresa->razon_social,
            'sucursal_id'=>$this->sucursal->lugar,
            'condicion'=> $this->condicion->nombre,
            'cantidad'=> $this->cantidad,
            // 'por_recibir'=> $this->por_recibir,
            // 'por_entregar'=> $this->por_entregar,
            // 'prestados'=>$this->prestados,
            'estado'=>$this->estado,
            'codigo'=>$this->codigo,
        ];

        return $modelo;
    }
}
