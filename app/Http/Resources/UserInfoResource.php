<?php

namespace App\Http\Resources;

use App\Models\Empleado;
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
        $empleado = $this->empleado;

        return [
            'id' => $this->empleado->id,
            'usuario' => Empleado::extraerNombresApellidos($empleado),
            'nombres' => $empleado->nombres,
            'apellidos' => $empleado->apellidos,
            'email' => $this->email,
            'identificacion' => $empleado->identificacion,
            'telefono' => $empleado->telefono,
            'fecha_nacimiento' => $empleado->fecha_nacimiento,
            'jefe_id' => $empleado->jefe_id,
            'usuario_id' => $this->id,
            'sucursal_id' => $empleado->sucursal_id,
            'grupo_id' => $empleado->grupo_id,
            'grupo' => $empleado->grupo?->nombre,
            'roles' => $this->getRoleNames(), // ->toArray()),
            'estado' => $empleado->estado,
            'es_lider' => $this->esTecnicoLider(),
            'permisos' => $this->obtenerPermisos($this->id),
            'cargo' => $empleado->cargo?->nombre,
            'departamento' => $empleado->departamento_id,
        ];
    }
}
