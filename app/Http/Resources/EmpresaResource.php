<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EmpresaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            "id"=>$this->id,
            'identificacion'=>$this->identificacion,
            'tipo_contribuyente'=>$this->tipo_contribuyente,
            'razon_social'=>$this->razon_social,
            'nombre_comercial'=>$this->nombre_comercial,
            'telefono'=>$this->telefono,
            'correo'=>$this->correo,
            'direccion'=>$this->direccion,
        ];
    }
}
