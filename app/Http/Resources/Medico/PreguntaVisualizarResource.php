<?php

namespace App\Http\Resources\Medico;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;

class PreguntaVisualizarResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'codigo' => $this->codigo,
            'pregunta' => $this->pregunta,
            'respuesta' =>$this->obtenerRespuesta($this->cuestionario->toArray()),
            'posibles_respuestas' =>  $this->cuestionario
        ];
    }
    public function obtenerRespuesta(array $cuestionarios)
    {
        $respuesta = array_map(function ($cuestionario) {
            return $cuestionario['respuestas_cuestionarios_empleados'] !== null ?$cuestionario['respuestas_cuestionarios_empleados']['cuestionario_id']:null;
        }, $cuestionarios);
        return $respuesta[0];
    }
}
