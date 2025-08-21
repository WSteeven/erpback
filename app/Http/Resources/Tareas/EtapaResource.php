<?php

namespace App\Http\Resources\Tareas;

use App\Http\Resources\BaseResource;

class EtapaResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array
     */
    protected function construirModelo()
    {
        $modelo =  [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'activo' => $this->activo,
            'supervisor_id' =>$this->responsable_id,
            'supervisor_responsable' => $this->responsable->nombres . ' ' . $this->responsable->apellidos,
            'responsable' => $this->obtenerInformacionResponsable(), //$this->responsable->nombres . ' ' . $this->responsable->apellidos,
            'codigo_proyecto' => $this->proyecto->codigo_proyecto,
            'proyecto' => $this->proyecto->nombre
        ];

        if ($this->controllerMethodIsShow()) {
            $modelo['supervisor_responsable'] = $this->responsable_id;
            $modelo['proyecto'] = $this->proyecto_id;
            $modelo['responsable'] = $this->responsable_id;
        }

        return $modelo;
    }

    protected function obtenerInformacionResponsable()
    {
        return $this->responsable->nombres . ' ' . $this->responsable->apellidos;
    }
}
