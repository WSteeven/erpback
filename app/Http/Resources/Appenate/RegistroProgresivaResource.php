<?php

namespace App\Http\Resources\Appenate;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RegistroProgresivaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        $controller_method = $request->route()->getActionMethod();
        $modelo = [
            'id' => $this->id,
            'progresiva_id' => $this->progresiva_id,
            'num_elemento' => $this->num_elemento,
            'propietario' => $this->propietario,
            'elemento' => $this->elemento,
            'tipo_poste' => $this->tipo_poste,
            'material_poste' => $this->material_poste,
            'ubicacion_gps' => $this->ubicacion_gps,
            'foto' => $this->foto,
            'observaciones' => $this->observaciones,
            'tiene_control_cambio' => $this->tiene_control_cambio,
            'observacion_cambio' => $this->observacion_cambio,
            'foto_cambio' => $this->foto_cambio,
            'hora_cambio' => $this->hora_cambio,
        ];

        if ($controller_method == 'show') {
            $modelo['materiales'] = MaterialUtilizadoProgresivaResource::collection($this->materiales);
        }

        return $modelo;
    }
}
