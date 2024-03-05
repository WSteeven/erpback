<?php

namespace App\Http\Resources\Medico;

use App\Http\Resources\BaseResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;

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
            'respuesta' => null,
            'cuestionario' =>  $this->mapearCuestionario(),
        ];
    }

    private function mapearCuestionario()
    {
        $var = $this->cuestionario->map(function ($cuestionario) {
            return [
                'id' => $cuestionario->id,
                // 'pregunta' => $cuestionario->pregunta_id, //?->pregunta,
                'respuesta' => $cuestionario->respuesta?->respuesta,
            ];
        });
        return $var;
    }
}
