<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UbicacionResource extends JsonResource
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
        $modelo = [
            'id'=>$this->id,
            'codigo'=>$this->codigo,
            'percha'=>$this->percha->nombre,
            'piso'=>$this->piso->fila,
        ];

        if($controller_method=='show'){
            $modelo['percha'] = $this->percha_id;
            $modelo['piso'] = $this->piso_id;
        }
        return $modelo;
    }
}
