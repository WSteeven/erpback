<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserInfoResource extends JsonResource
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
            'id' => $this->empleado->id,
            'nombres' => $this->empleado->nombres,
            'apellidos' => $this->empleado->apellidos,
            'email' => $this->email,
            'identificacion' => $this->empleado->identificacion,
            'telefono' => $this->empleado->telefono,
            'fecha_nacimiento' => $this->empleado->fecha_nacimiento,
            'jefe_id' => $this->empleado->jefe_id,
            'usuario_id' => $this->id,
            'sucursal_id' => $this->empleado->sucursal_id,
            'grupo_id' => $this->empleado->grupo_id,
            'grupo' => $this->empleado->grupo?->nombre,
            'roles' => $this->getRoleNames(), // ->toArray()),
            'estado' => $this->empleado->estado,
            'es_lider' => $this->esTecnicoLider(),
            'permisos' => $this->obtenerPermisos($this->id),
            'cargo' => $this->empleado->cargo?->nombre,
        ];
    }
}
