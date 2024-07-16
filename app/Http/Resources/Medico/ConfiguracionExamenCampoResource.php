<?php

namespace App\Http\Resources\Medico;

use Illuminate\Http\Resources\Json\JsonResource;

class ConfiguracionExamenCampoResource extends JsonResource
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
            'campo' => $this->campo,
            'unidad_medida' => $this->unidad_medida,
            'rango_inferior' => $this->rango_inferior ? number_format($this->rango_inferior, 2) : null,
            'rango_superior' => $this->rango_superior ? number_format($this->rango_superior, 2) : null,
            'configuracion_examen_categoria_id' => $this->configuracion_examen_categoria_id,
            'configuracion_examen_categoria' => $this->configuracionExamenCategoria?->nombre,
        ];
    }
}
