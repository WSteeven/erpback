<?php

namespace App\Http\Resources\FondosRotativos;

use App\Models\Empleado;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ValijaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $controller_method = $request->route()->getActionMethod();
        $modelo = [
            'id'=>$this->id,
            'gasto_id'=>$this->gasto_id,
            'empleado_id'=>$this->empleado_id,
            'empleado'=>Empleado::extraerNombresApellidos($this->empleado),
            'departamento_id'=>$this->departamento_id,
            'departamento'=>$this->departamento?->nombre,
            'descripcion'=>$this->descripcion,
            'destinatario_id'=>$this->destinatario_id,
            'imagen_evidencia'=>url($this->imagen_evidencia),
        ];

//        if($controller_method == 'show'){
//                                      $modelo['empleado'] = $this->empleado_id;
//                                      $modelo['departamento'] = $this->departamento_id;
//        }

        return $modelo;

    }
}
