<?php

namespace App\Http\Resources\Tareas;

use Illuminate\Http\Resources\Json\JsonResource;

class CoordenadasResource extends JsonResource
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
            'subtarea' => $this->subtarea_id,
            'tipo' => $this->tipo?->nombre,
            'nombre' => $this->nombre,
            'latitud' => $this->latitud,
            'longitud' => $this->longitud,
            'direccion' => $this->direccion,
            'observacion' => $this->observacion,
        ];

        if ($controller_method == 'show') {
            $modelo['tipo'] = $this->tipo_id;
        }

        return $modelo;
    }
}
