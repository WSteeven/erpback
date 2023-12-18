<?php

namespace App\Http\Resources\Ventas;

use Illuminate\Http\Resources\Json\JsonResource;

class VendedorResource extends JsonResource
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
            'codigo_vendedor' => $this->codigo_vendedor,
            'empleado' => $this->empleado_id,
            'empleado_id' => $this->empleado_id,
            'empleado_info'=>$this->empleado !=null?$this->empleado->nombres.''.$this->empleado->apellidos:'',
            'modalidad' => $this->modalidad_id,
            'modalidad_id' => $this->modalidad_id,
            'modalidad_info'=> $this->modalidad!=null?$this->modalidad->nombre:'',
            'tipo_vendedor'=> $this->tipo_vendedor
        ];
        return $modelo;
    }
}
