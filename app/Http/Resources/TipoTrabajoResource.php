<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TipoTrabajoResource extends JsonResource
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
            'descripcion' => $this->descripcion,
            'activo' => $this->activo,
            'cliente' => $this->cliente?->empresa->razon_social,
            'cliente_id' => $this->cliente_id,
        ];

        if ($controller_method == 'show') {
            $modelo['cliente'] = $this->cliente_id;
            // $modelo['imagenes_adicionales'] = $this->imagenes_adicionales;
            // $modelo['campos_adicionales'] = $this->campos_adicionales;
        }

        return $modelo;
    }
}
