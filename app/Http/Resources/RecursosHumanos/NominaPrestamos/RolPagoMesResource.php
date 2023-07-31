<?php

namespace App\Http\Resources\RecursosHumanos\NominaPrestamos;

use Illuminate\Http\Resources\Json\JsonResource;

class RolPagoMesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $modelo = [
            'id' => $this->id,
            'mes' => $this->mes,
            'nombre' => $this->nombre,
            'finalizado' => $this->finalizado,
            'cantidad_roles_empleado' => $this->rolPago->count(),
        ];
        return $modelo;
    }
}
