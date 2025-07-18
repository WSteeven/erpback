<?php

namespace App\Http\Resources\ComprasProveedores;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CalificacionDepartamentoProveedorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'detalle_departamento_id'=>$this->detalle_departamento_id,
            'nombre'=>$this->criterio_calificacion?->nombre,
            'tipo'=>$this->criterio_calificacion?->oferta->nombre,
            'comentario'=>$this->comentario,
            'peso'=>$this->peso,
            'puntaje'=>$this->puntaje,
            'calificacion'=>$this->calificacion,

        ];
    }
}
