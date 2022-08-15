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
            'id' => $this->id,
            'nombres' => $this->empleados->nombres,
            'apellidos' => $this->empleados->apellidos,
            'email' => $this->email,
            'identificacion' => $this->empleados->identificacion,
            'telefono' => $this->empleados->telefono,
            'fecha_nacimiento' => $this->empleados->fecha_nacimiento,
            'jefe' => $this->empleados->jefes,
            'localidad' => $this->empleados->localidad_id,
        ];
    }
}
