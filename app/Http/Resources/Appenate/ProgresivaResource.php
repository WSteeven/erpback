<?php

namespace App\Http\Resources\Appenate;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProgresivaResource extends JsonResource
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
            'metadatos' => $this->metadatos,
            'filename' => $this->filename,
            'proyecto' => $this->proyecto,
            'ciudad' => $this->ciudad,
            'enlace' => $this->enlace,
            'fecha_instalacion' => $this->fecha_instalacion,
            'cod_bobina' => $this->cod_bobina,
            'mt_inicial' => $this->mt_inicial,
            'mt_final' => $this->mt_final,
            'fo_instalada' => $this->fo_instalada,
            'num_tarea' => $this->num_tarea,
            'hilos' => $this->hilos,
            'responsable' => $this->responsable,
        ];

        if ($controller_method == 'show') {
            $modelo['registros_progresivas'] = RegistroProgresivaResource::collection($this->registros);
        }

        return $modelo;
    }
}
