<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EmpleadoResource extends JsonResource
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
            'identificacion'=>$this->identificacion,
            'nombres'=>$this->nombres,
            'apellidos'=>$this->apellidos,
            'telefono'=>$this->telefono,
            'fecha_nacimiento'=>$this->fecha_nacimiento,
            'jefe_id'=>$this->jefe_id,
            'usuario_id'=>$this->usuario_id,
            'sucursal_id'=>$this->sucursal_id,
        ];
    }
}
