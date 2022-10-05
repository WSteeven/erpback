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
            'id'=>$this->id,
            'identificacion'=>$this->identificacion,
            'nombres'=>$this->nombres,
            'apellidos'=>$this->apellidos,
            'telefono'=>$this->telefono,
            'fecha_nacimiento'=>$this->fecha_nacimiento,
            'email'=>$this->user? $this->user->email:'',
            'jefe'=>$this->jefe? $this->jefe->nombres.' '.$this->jefe->apellidos:'N/A',
            'usuario'=>$this->user->name,
            'sucursal'=>$this->sucursal->lugar,
            'estado'=>$this->estado,
            'roles'=>implode(', ',$this->user->getRoleNames()->toArray())
        ];

        if($controller_method=='show'){
            $modelo['jefe'] = $this->jefe_id;
            $modelo['usuario'] = $this->usuario_id;
            $modelo['sucursal'] = $this->sucursal_id;
            $modelo['roles'] = $this->user->getRoleNames();
        }

        return $modelo;
    }
}
