<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{

    public static $wrap = null;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->empleados->id,
            'nombres' => $this->empleados->nombres,
            'apellidos' => $this->empleados->apellidos,
            'email' => $this->email,
            'identificacion' => $this->empleados->identificacion,
            'telefono' => $this->empleados->telefono,
            'fecha_nacimiento' => $this->empleados->fecha_nacimiento,
            'jefe_id' => $this->empleados->jefe_id,
            'usuario_id' => $this->id,
            'sucursal_id' => $this->empleados->sucursal_id,
            'grupo_id' => $this->empleados->grupo_id,
            'rol'=>$this->getRoleNames(),
            'estado' => $this->empleados->estado,
        ];
    }
}
