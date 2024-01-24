<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoriaTipoTicketResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $controller_method = $request->route()->getActionMethod();

        $modelo = [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'departamento' => $this->departamento->nombre,
            'departamento_id' => $this->departamento_id,
            'activo' => $this->activo,
        ];

        if ($controller_method == 'show') {
            $modelo['departamento'] = $this->departamento_id;
        }

        return $modelo;
    }
}
