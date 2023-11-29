<?php

namespace App\Http\Resources\Tareas;

use Illuminate\Http\Resources\Json\JsonResource;

class EtapaResource extends JsonResource
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
            'id'=>$this->id,
            'nombre'=>$this->nombre,
            'activo'=>$this->activo,
            'responsable'=>$this->responsable->nombres.' '.$this->responsable->apellidos,
            'codigo_proyecto'=>$this->proyecto->codigo_proyecto,
            'proyecto'=>$this->proyecto->nombre
        ];

        if($controller_method=='show'){
            $modelo['responsable'] = $this->responsable_id;
            $modelo['proyecto'] = $this->proyecto_id;
        }

        return $modelo;
    }
}
