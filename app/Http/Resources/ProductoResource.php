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
        $controller_method = $request->route()->getActionMethod();
        $modelo = [
            'id' => $this->id,
            'cantidad'=>$this->cantidadDetalles($this->id),
            'nombre' => $this->nombre,
            'categoria' => $this->categoria?->nombre,
            'unidad_medida' => $this->unidadMedida?->simbolo,
            // 'categoria' => $this->categoria->nombre,
        ];

        if ($controller_method == 'show') {
            // $modelo['categoria'] = $this->categoria->nombre;
            $modelo['categoria'] = $this->categoria_id;
            $modelo['unidad_medida'] = $this->unidad_medida_id;

        }
        
        return $modelo;
    }
}
