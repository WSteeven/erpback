<?php

namespace App\Http\Resources\RecursosHumanos\NominaPrestamos;

use App\Models\Empleado;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PlanVacacionResource extends JsonResource
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
            'periodo'=>$this->periodo->nombre,
            'empleado'=>Empleado::extraerNombresApellidos($this->empleado),
            'rangos'=>$this->rangos,
            'fecha_inicio'=>$this->fecha_inicio,
            'fecha_fin'=>$this->fecha_fin,
            'fecha_inicio_primer_rango'=>$this->fecha_inicio_primer_rango,
            'fecha_fin_primer_rango'=>$this->fecha_fin_primer_rango,
            'fecha_inicio_segundo_rango'=>$this->fecha_inicio_segundo_rango,
            'fecha_fin_segundo_rango'=>$this->fecha_fin_segundo_rango,
        ];

        if($controller_method == 'show'){
            $modelo['periodo']=$this->periodo_id;
            $modelo['empleado']=$this->empleado_id;
        }

        return $modelo;
    }
}
