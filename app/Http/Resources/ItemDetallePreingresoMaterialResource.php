<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ItemDetallePreingresoMaterialResource extends JsonResource
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
            'preingreso' => $this->preingreso_id,
            'detalle_id' => $this->detalle_id,
            'descripcion' => $this->descripcion,
            'producto' => $this->detalle->producto->nombre,
            'cantidad' => $this->cantidad,
            'serial' => $this->serial,
            'punta_inicial' => $this->punta_inicial,
            'punta_final' => $this->punta_final,
            'unidad_medida' => $this->unidadMedida->nombre,
            'fotografia' => $this->fotografia,
            'created_at' => date('d/m/Y', strtotime($this->created_at)),
        ];
    }
}
