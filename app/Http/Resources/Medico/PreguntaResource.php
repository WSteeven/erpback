<?php

namespace App\Http\Resources\Medico;

use Illuminate\Http\Resources\Json\JsonResource;

class PreguntaResource extends JsonResource
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
            'respuesta' => $this->obtenerRespuesta( $this->cuestionario),
            'posibles_respuestas' =>  $this->cuestionario
        ];
    }
    public function obtenerRespuesta($cuestionarios){
        $dobles = array_map(function($cuestionario) {
            return $cuestionario;
        }, $cuestionarios);
        return $dobles;
    }
}
