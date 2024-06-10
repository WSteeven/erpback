<?php

namespace App\Http\Resources\Medico;

use App\Http\Resources\BaseResource;
// use Illuminate\Http\Resources\Json\JsonResource;

class LaboratorioClinicoResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function construirModelo($request)
    {
        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'direccion' => $this->direccion,
            'celular' => $this->celular,
            'correo' => $this->correo,
            'coordenadas' => $this->coordenadas,
            'activo' => $this->activo,
            'canton_id' => $this->canton_id,
        ];
    }
}
