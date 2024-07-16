<?php

namespace App\Http\Resources\Vehiculos;

use Illuminate\Http\Resources\Json\JsonResource;

class ChecklistImagenVehiculoResource extends JsonResource
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
            'bitacora_id' => $this->bitacora_id,
            'imagen_frontal' => $this->imagen_frontal ? url($this->imagen_frontal) : null,
            'imagen_trasera' => $this->imagen_trasera ? url($this->imagen_trasera) : null,
            'imagen_lateral_derecha' => $this->imagen_lateral_derecha ? url($this->imagen_lateral_derecha) : null,
            'imagen_lateral_izquierda' => $this->imagen_lateral_izquierda ? url($this->imagen_lateral_izquierda) : null,
            'imagen_tablero_km' => $this->imagen_tablero_km ? url($this->imagen_tablero_km) : null,
            'imagen_tablero_radio' => $this->imagen_tablero_radio ? url($this->imagen_tablero_radio) : null,
            'imagen_asientos' => $this->imagen_asientos ? url($this->imagen_asientos) : null,
            'imagen_accesorios' => $this->imagen_accesorios ? url($this->imagen_accesorios) : null,
            'observacion' => $this->observacion,
        ];
    }
}
