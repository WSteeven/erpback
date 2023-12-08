<?php

namespace App\Http\Resources\Tareas;

use App\Http\Resources\BaseResource;
use Illuminate\Http\Resources\Json\JsonResource;

class EtapaResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    protected function construirModelo($request)
    {
        $modelo =  [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'activo' => $this->activo,
            'responsable' => $this->responsable->nombres . ' ' . $this->responsable->apellidos,
            'codigo_proyecto' => $this->proyecto->codigo_proyecto,
            'proyecto' => $this->proyecto->nombre
        ];

        if ($this->controllerMethodIsShow()) {
            $modelo['responsable'] = $this->responsable_id;
            $modelo['proyecto'] = $this->proyecto_id;
        }

        return $modelo;
    }
}
