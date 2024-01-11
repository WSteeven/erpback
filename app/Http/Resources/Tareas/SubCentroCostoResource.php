<?php

namespace App\Http\Resources\Tareas;

use Illuminate\Http\Resources\Json\JsonResource;

class SubCentroCostoResource extends JsonResource
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
            'nombre' => $this->nombre,
            'centro_costo' => $this->centro->nombre,
            'activo' => $this->activo,
        ];

        if ($controller_method == 'show') {
            $modelo['centro_costo'] = $this->centro_costo_id;
        }

        return $modelo;
    }
}
