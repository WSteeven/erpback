<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProveedorResource extends JsonResource
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
        $controller_method = $request->route()->getActionMethod();
        return $modelo = [
            'id'=>$this->id,
            'empresa'=>$this->empresa_id,
            'razon_social'=>$this->empresa->razon_social,
            'estado'=>$this->estado,

        ];
    }
}
