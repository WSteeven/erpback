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
        $controller_method = $request->route()->getActionMethod();
        $modelo = [
            'id' => $this->id,
            'identificacion' => $this->identificacion,
            'nombres' => $this->nombres,
            'apellidos' => $this->apellidos,
            'telefono' => $this->telefono,
            'fecha_nacimiento' => $this->fecha_nacimiento,
            'email' => $this->user ? $this->user->email : '',
            // 'password'=>bcrypt($this->user->password),
            'usuario' => $this->user->name,
            'jefe' => $this->jefe ? $this->jefe->nombres . ' ' . $this->jefe->apellidos : 'N/A',
            'canton' => $this->canton? $this->canton->canton:'NO TIENE',
            'estado' => $this->estado,//?Empleado::ACTIVO:Empleado::INACTIVO,
            'cargo' => $this->cargo?->nombre,
            'grupo' => $this->grupo?->nombre,
            'grupo_id' => $this->grupo?->nombre,
            'disponible' => $this->disponible,
            'roles' => implode(', ', $this->user->getRoleNames()->toArray()),
            'cargo' => $this->cargo?->nombre,
            // 'es_lider' => $this->esTecnicoLider(),
        ];

        if ($controller_method == 'show') {
            $modelo['jefe'] = $this->jefe_id;
            $modelo['usuario'] = $this->user->name;
            $modelo['canton'] = $this->canton_id;
            $modelo['roles'] = $this->user->getRoleNames();
            $modelo['grupo'] = $this->grupo_id;
            $modelo['cargo'] = $this->cargo_id;
        }

        return $modelo;
    }
}
