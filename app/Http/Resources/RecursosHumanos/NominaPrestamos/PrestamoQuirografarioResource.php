<?php

namespace App\Http\Resources\RecursosHumanos\NominaPrestamos;

use App\Models\Empleado;
use Illuminate\Http\Resources\Json\JsonResource;

class PrestamoQuirografarioResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'mes' => $this->mes,
            'nut' => $this->nut,
            'valor' => $this->valor,
            'empleado' => $this->empleado_id,
            'empleado_info' => Empleado::extraerNombresApellidos($this->empleado),
        ];
    }
}
