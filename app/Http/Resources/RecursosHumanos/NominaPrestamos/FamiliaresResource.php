<?php

namespace App\Http\Resources\RecursosHumanos\NominaPrestamos;

use Illuminate\Http\Resources\Json\JsonResource;

class FamiliaresResource extends JsonResource
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
            'identificacion' =>$this->identificacion,
            'parentezco' =>$this->parentezco,
            'nombres' =>$this->nombres,
            'apellidos' =>$this->apellidos,
            'empleado' => $this->empleado_id,
            'empleado_info' => $this->empleado_info != null ? $this->empleado_info->nombres . ' ' . $this->empleado_info->apellidos : '',
        ];
        return $modelo;    }
}
