<?php

namespace App\Http\Resources\FondosRotativos;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EnvioValijaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'gasto_id' => $this->gasto_id,
            'empleado_id' => $this->empleado_id,
            'courier' => $this->courier,
            'fotografia_guia' => url($this->fotografia_guia),
            'anulado' => $this->anulado
        ];
    }
}
