<?php

namespace App\Http\Resources\Vehiculos;

use Illuminate\Http\Resources\Json\JsonResource;

class RegistroIncidenteResource extends JsonResource
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
            'vehiculo' => $this->vehiculo?->placa,
            'fecha' => $this->fecha,
            'descripcion' => $this->descripcion,
            'tipo' => $this->tipo,
            'gravedad' => $this->gravedad,
            'persona_reporta' => $this->personaReporta->nombres . ' ' . $this->personaReporta->apellidos,
            'persona_registra' => $this->personaRegistra->nombres . ' ' . $this->personaRegistra->apellidos,
            'aplica_seguro' => $this->aplica_seguro,
        ];

        if ($controller_method == 'show') {
            $modelo['vehiculo'] = $this->vehiculo_id;
            $modelo['persona_reporta'] = $this->persona_reporta_id;
            $modelo['persona_registra'] = $this->persona_registra_id;
        }

        return $modelo;
    }
}
