<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ModeloResource extends JsonResource
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
            'nombre'=>$this->nombre,
            'marca'=>$this->marca->nombre,
        ];

        if($controller_method=='show'){
            $modelo['marca'] = $this->marca_id;
        }
        
        return $modelo;
    }
}
