<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ImagenProductoResource extends JsonResource
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
            'url'=>$this->url,
            'detalle'=>$this->detalle->producto->nombre.' | '.$this->detalle->descripcion
            //'detalle'=>$this->detalle->descripcion
        ];

        if($controller_method == 'show'){
            $modelo['detalle'] = $this->detalle_id;
        }

        return $modelo;
    }
}
