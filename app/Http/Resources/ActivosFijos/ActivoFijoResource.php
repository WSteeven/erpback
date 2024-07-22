<?php

namespace App\Http\Resources\ActivosFijos;

use Illuminate\Http\Resources\Json\JsonResource;

class ActivoFijoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $detalleProducto = $this->detalleProducto;

        return [
            'id' => $this->id,
            'codigo' => 'AF' . $this->id,
            'descripcion' => $detalleProducto->descripcion,
            'serie' => $detalleProducto->serial,
            'fecha_caducidad' => $this->fecha_caducidad,
            'unidad_medida' => $detalleProducto->producto->unidadMedida->nombre,
            'ingresos' => $this->ingresos,
            'egresos' => $this->egresos,
            'diferencia' => $this->diferencia,
        ];
    }
}
