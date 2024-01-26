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
        $user = $this->empleado->user;

        return [
            'id' => $this->empleado->id,
            'identificacion' => $this->empleado->identificacion,
            'nombres' => $this->empleado->nombres,
            'apellidos' => $this->empleado->apellidos,
            'email' => $this->email,
            'departamento' => $this->empleado->departamento?->nombre,
            'roles' => $user ? implode(', ', $user->getRoleNames()->filter(fn ($rol) => $rol !== 'EMPLEADO')->toArray()) : [],
        ];
    }
}
