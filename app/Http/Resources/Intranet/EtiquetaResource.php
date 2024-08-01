<?php

namespace App\Http\Resources\Intranet;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EtiquetaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        $controller_method = $request->route()->getActionMethod();
        $modelo = [
            'id' => $this->id,
            'categoria' => $this->categoria->nombre,
            'categoria_id' => $this->categoria_id,
            'nombre' => $this->nombre,
            'activo' => $this->activo
        ];

        if ($controller_method == 'show') {
            $modelo['categoria'] = $this->categoria_id;
        }
        return $modelo;

    }
}
