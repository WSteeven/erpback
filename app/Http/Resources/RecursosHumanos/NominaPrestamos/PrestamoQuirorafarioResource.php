<?php

namespace App\Http\Resources\RecursosHumanos\NominaPrestamos;

use Illuminate\Http\Resources\Json\JsonResource;

class PrestamoQuirorafarioResource extends JsonResource
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
            'nut' => $this->nut,
            'valor' =>  $this->valor,
            'empleado' => $this->empleado_id,
            'empleado_info' => $this->empleado_info->nombres.' '.$this->empleado_info->apellidos,
        ];
        return $modelo;
    }
}
