<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SucursalResource extends JsonResource
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

        $modelo =  [
            'id' => $this->id,
            'lugar' => $this->lugar,
            'telefono' => $this->telefono,
            'correo' => $this->correo,
            'extension' => $this->extension,
            // 'administrador' => $this->administrador?->empleado->nombres.' '.$this->administrador?->empleado->apellidos,
            // 'administrador_id'=>$this->administrador_id,
        ];

        /* if($controller_method=='show'){
            $modelo['administrador'] = $this->administrador_id;
        } */

        return $modelo;
    }
}
