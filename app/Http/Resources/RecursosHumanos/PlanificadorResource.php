<?php

namespace App\Http\Resources\RecursosHumanos;

use App\Models\Empleado;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PlanificadorResource extends JsonResource
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
            'empleado'=>Empleado::extraerNombresApellidos($this->empleado),
            'nombre'=>$this->nombre,
            'completado'=>$this->completado,
            'actividades'=>$this->actividades,
        ];

        if($controller_method == 'show'){
            $modelo['empleado']= $this->empleado_id;
        }

        return $modelo;
    }
}
