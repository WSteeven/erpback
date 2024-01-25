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
            'intervalo_referencia' => $this->intervalo_referencia,
            'configuracion_examen_categoria' => $this->configuracionExamenCategoria,
            'configuracion_examen_categoria_info' => $this->configuracionExamenCategoria !==null ?$this->configuracionExamenCategoria?->nombre:' ',
        ];    }
}
