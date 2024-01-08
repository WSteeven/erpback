<?php

namespace App\Http\Resources\RecursosHumanos\Alimentacion;

use Illuminate\Http\Resources\Json\JsonResource;

class AlimentacionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $modelo = [
            'id' => $this->id,
            'mes' => $this->mes,
            'nombre' => $this->nombre,
            'finalizado' => $this->finalizado,
            'es_quincena' => $this->es_quincena,
        ];
        return $modelo;
    }
}
