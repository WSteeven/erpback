<?php

namespace App\Http\Resources\RecursosHumanos\Capacitacion;

use App\Models\Empleado;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EvaluacionDesempenoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        $controller_method = $request->route()->getActionMethod();
        $modelo = [
            'id' => $this->id,
            'evaluado' => Empleado::extraerNombresApellidos($this->evaluado),
            'evaluador' => Empleado::extraerNombresApellidos($this->evaluador),
            'formulario' => $this->formulario->nombre,
            'calificacion' => $this->calificacion,
        ];
        if ($controller_method == 'show') {
            $modelo['evaluado'] = $this->evaluado_id;
            $modelo['evaluador'] = $this->evaluador_id;
            $modelo['formulario'] = $this->formulario_id;
            $modelo['respuestas'] = $this->respuestas;
        }

        return $modelo;
    }
}
