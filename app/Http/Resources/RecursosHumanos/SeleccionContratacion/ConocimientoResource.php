<?php

namespace App\Http\Resources\RecursosHumanos\SeleccionContratacion;

use Illuminate\Http\Resources\Json\JsonResource;

class ConocimientoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $controller_method = $request->route()->getActionMethod();
        $modelo =  [
            'id' => $this->id,
            'cargo' => $this->cargo?->nombre,
            'nombre' => $this->nombre,
            'activo' => $this->activo
        ];

        if ($controller_method == 'show') {
            $modelo['cargo'] = $this->cargo_id;
        }

        return $modelo;
    }
}
