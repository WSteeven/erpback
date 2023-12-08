<?php

namespace App\Http\Resources\FondosRotativos;

use Illuminate\Http\Resources\Json\JsonResource;

class UmbralFondosRotativosResource extends JsonResource
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
            'empleado'=>$this->empleado_id,
            'empleado_info'=>$this->empleado->nombres.' '.$this->empleado->apellidos,
            'empleado_id'=>$this->empleado_id,
            'valor_minimo'=>$this->valor_minimo,
            'referencia'=>$this->referencia,
        ];
        return $modelo;
    }
}
