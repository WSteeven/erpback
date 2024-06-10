<?php

namespace App\Http\Resources\RecursosHumanos\SeleccionContratacion;

use Illuminate\Http\Resources\Json\JsonResource;

class SolicitudPuestoEmpleoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $modelo = [ 'id' => $this->id,
        'descripcion' => $this->descripcion,
        'anos_experiencia' => $this->anos_experiencia,
        'tipo_puesto' => $this->tipo_puesto_id,
        'tipo_puesto_info' => $this->tipoPuesto,
        'cargo' => $this->cargo_id,
        'cargo_info' => $this->cargo,
        'autorizacion' => $this->autorizacion_id,
        'autorizacion_info' => $this->autorizacion
    ];
        return $modelo;
    }
}
