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
        $controller_method = request()->route()->getActionMethod();

        $modelo = [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'direccion' => $this->direccion,
            'celular' => $this->celular,
            'correo' => $this->correo,
            'coordenadas' => $this->coordenadas,
            'activo' => $this->activo,
            'canton' => $this->canton->canton,
            'activo' => $this->activo,
        ];

        if ($controller_method == 'show') {
            $modelo['canton'] = $this->canton_id;
        }

        return $modelo;
    }
}
