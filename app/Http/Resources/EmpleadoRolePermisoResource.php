<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EmpleadoRolePermisoResource extends JsonResource
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
            'id' => $this->empleado->id,
            'identificacion' => $this->empleado->identificacion,
            'nombres' => $this->empleado->nombres,
            'apellidos' => $this->empleado->apellidos,
            'email' => $this->email,
            'departamento' => $this->empleado->departamento?->nombre,
        ];
    }
}
