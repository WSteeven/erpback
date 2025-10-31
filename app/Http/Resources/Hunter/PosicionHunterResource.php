<?php

namespace App\Http\Resources\Hunter;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PosicionHunterResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'source' => $this->source,
            'imei' => $this->imei,
            'placa' => $this->placa,
            'lat' => $this->lat,
            'lng' => $this->lng,
            'velocidad' => $this->velocidad,
            'rumbo' => $this->rumbo,
            'alt' => $this->alt,
            'fecha' => $this->fecha,
            'encendido' => (bool)$this->tipo_reporte[0],
            'direccion' => $this->direccion,
            'tipo_reporte' => $this->tipo_reporte,
            'estado' => $this->estado,
            'flags_binarios' => $this->flags_binarios,
            'flags' => $this->flags,
            'raw_data' => $this->raw_data,
            'received_at' => $this->received_at,
            'coordenadas' => $this->mapearCoordenadas()
        ];
    }

    private function mapearCoordenadas()
    {
        return ['lat' => $this->lat,
            'lng' => $this->lng,
            'titulo' => $this->placa,
            'descripcion' => "Encendido: $this->encendido,  $this->tipo_reporte",
        ];
    }
}
