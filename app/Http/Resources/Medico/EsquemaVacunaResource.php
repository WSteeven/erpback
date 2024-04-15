<?php

namespace App\Http\Resources\Medico;

use Illuminate\Http\Resources\Json\JsonResource;

class EsquemaVacunaResource extends JsonResource
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
            'id' => $this->id,
            'dosis_aplicadas' => $this->dosis_aplicadas,
            'dosis_totales' => $this->tipoVacuna?->dosis_totales,
            'observacion' => $this->observacion,
            'tipo_vacuna' => $this->tipoVacuna?->nombre,
            'tipo_vacuna_id' => $this->tipo_vacuna_id,
        ];

        if ($controller_method == 'show') {
            $modelo['tipo_vacuna'] = $this->tipo_vacuna_id;
        }

        return $modelo;
    }
}
