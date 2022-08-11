<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductoResource extends JsonResource
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
            'codigo_barras' => $this->codigo_barras,
            'nombre_id' => $this->nombre_id,
            'descripcion' => $this->descripcion,
            'modelo_id' => $this->modelo_id,
            'precio' => $this->precio,
            'serial' => $this->serial,
            'categoria_id' => $this->categoria_id,
            //'estado' => $this->estado
        ];
    }
}
