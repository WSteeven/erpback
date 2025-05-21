<?php

namespace App\Http\Resources\Appenate;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MaterialUtilizadoProgresivaResource extends JsonResource
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
//            'registro_id' => $this->registro_id,
            'material' => $this->material,
            'cantidad' => $this->cantidad,
        ];
    }
}
