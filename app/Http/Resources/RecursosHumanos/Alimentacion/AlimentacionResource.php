<?php

namespace App\Http\Resources\RecursosHumanos\Alimentacion;

use Illuminate\Http\Resources\Json\JsonResource;

class AlimentacionResource extends JsonResource
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
            'empleado' => $this->empleado_id,
            'empleado_info' => $this->empleado != null ? $this->empleado->nombres . ' ' . $this->empleado->apellidos : '',
            'valor_asignado' => $this->valor_asignado,
            'fecha_corte' => $this->fecha_corte
        ];
    }
}
