<?php

namespace App\Http\Resources\Medico;

use App\Http\Resources\BaseResource;

// use Illuminate\Http\Resources\Json\JsonResource;

class LaboratorioClinicoResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array
     */
    public function construirModelo()
    {
        $controller_method = request()->route()->getActionMethod();

        $modelo = [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'direccion' => $this->direccion,
            'celular' => $this->celular,
            'correo' => $this->correo,
            'coordenadas' => $this->coordenadas,
            'canton' => $this->canton->canton,
            'activo' => $this->activo,
        ];

        if ($controller_method == 'show') {
            $modelo['canton'] = $this->canton_id;
        }

        return $modelo;
    }
}
