<?php

namespace App\Http\Resources\Ventas;

use Illuminate\Http\Resources\Json\JsonResource;

class ClienteClaroResource extends JsonResource
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
            'id' => $this->id,
            'identificacion' => $this->identificacion,
            'nombres' => $this->nombres,
            'apellidos' => $this->apellidos,
            'cliente_info' => $this->nombres . ' ' . $this->apellidos,
            'direccion' => $this->direccion,
            'telefono1' => $this->telefono1,
            'telefono2' => $this->telefono2,
            'activo' => $this->activo,
        ];
        return $modelo;
    }
}
