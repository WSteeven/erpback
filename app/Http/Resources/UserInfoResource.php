<?php

namespace App\Http\Resources;

use App\Models\Empleado;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;

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
            'id' => $this->empleado !=null ? $this->empleado->id:0,
            'usuario' => $this->empleado !=null ?Empleado::extraerNombresApellidos($empleado):'',
            'nombres' => $this->empleado !=null ?$empleado->nombres:'',
            'apellidos' => $this->empleado !=null ?$empleado->apellidos:'',
            'email' => $this->email,
            'identificacion' =>  $this->empleado !=null ?$empleado->identificacion:'',
            'telefono' =>  $this->empleado !=null ?$empleado->telefono:'',
            'fecha_nacimiento' =>  $this->empleado !=null ?$empleado->fecha_nacimiento:'',
            'jefe_id' =>$this->empleado !=null ? $empleado->jefe_id:0,
            'usuario_id' => $this->id,
            'sucursal_id' =>  $this->empleado !=null ?$empleado->sucursal_id:'',
            'grupo_id' => $this->empleado !=null ? $empleado->grupo_id :0,
            'grupo' => $this->empleado !=null ?$empleado->grupo?->nombre:'',
            'roles' => $this->getRoleNames(), // ->toArray()),
            'estado' => $this->empleado !=null ?$empleado->estado:false,
            'es_lider' => $this->esTecnicoLider(),
            'permisos' => $this->obtenerPermisos($this->id),
            'cargo' => $this->empleado !=null ?$empleado->cargo?->nombre:'',
            'departamento' => $this->empleado !=null ?$empleado->departamento_id:0,
        ];
    }
}
