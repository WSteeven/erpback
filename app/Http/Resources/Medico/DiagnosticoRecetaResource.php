<?php

namespace App\Http\Resources\Medico;

use Illuminate\Http\Resources\Json\JsonResource;

class DiagnosticoRecetaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    // Recibe receta
    public function toArray($request)
    {
        return [
            '' => $this->recomendacion,
            'rp' => $this->rp,
            'prescripcion' => $this->rp,
            'diagnosticos' => $this->diagnosticos,
        ];
    }
}
