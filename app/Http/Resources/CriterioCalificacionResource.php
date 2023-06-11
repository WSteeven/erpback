<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CriterioCalificacionResource extends JsonResource
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
        $modelo = [
            'nombre' => $this->nombre,
            'descripcion' => $this->descripcion,
            'ponderacion_referencia' => $this->ponderacion_referencia,
            'departamento' => $this->departamento->nombre,
            'oferta' => $this->oferta->nombre,
        ];

        if ($controller_method == 'show') {
            $modelo['departamento'] = $this->departamento_id;
            $modelo['oferta'] = $this->oferta_id;
        }

        return $modelo;
    }
}
