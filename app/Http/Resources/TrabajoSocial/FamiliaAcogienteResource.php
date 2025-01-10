<?php

namespace App\Http\Resources\TrabajoSocial;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FamiliaAcogienteResource extends JsonResource
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
            'vivienda_id' => $this->vivienda_id,
            'canton_id' => $this->canton_id,
            'parroquia_id' => $this->parroquia_id,
            'tipo_parroquia' => $this->tipo_parroquia,
            'direccion' => $this->direccion,
            'coordenadas' => $this->coordenadas,
            'nombres_apellidos' => $this->nombres_apellidos,
            'telefono' => $this->telefono,
        ];
    }
}
