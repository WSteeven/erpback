<?php

namespace App\Http\Resources\Medico;

use Illuminate\Http\Resources\Json\JsonResource;

class AptitudMedicaResource extends JsonResource
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
            'tipo_aptitud' => $this->tipo_aptitud_id,
            'tipo_aptitud_info' => $this->tipoAptitud !==null ? $this->tipoAptitud?->nombre:'',
            'observacion' => $this->observacion,
            'limitacion' => $this->limitacion,
            'preocupacional_id' => $this->preocupacional_id,
        ];
    }
}
