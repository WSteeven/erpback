<?php

namespace App\Http\Resources\Seguridad;

use Illuminate\Http\Resources\Json\JsonResource;

class RestriccionPrendaZonaResource extends JsonResource
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
            'id' => $this['id'],
            'detalle_producto_id' => $this['detalle_producto_id'],
            'detalle_producto' => $this->detalleProducto?->descripcion,
            'miembro_zona_id' => $this['miembro_zona_id'],
        ];
    }
}
