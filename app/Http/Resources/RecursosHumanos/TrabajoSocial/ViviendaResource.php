<?php

namespace App\Http\Resources\RecursosHumanos\TrabajoSocial;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ViviendaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'empleado_id' => $this->empleado_id,
            'tipo' => $this->tipo,
            'material_paredes' => $this->material_paredes,
            'material_techo' => $this->material_techo,
            'material_piso' => $this->material_piso,
            'distribucion_vivienda' => $this->distribucion_vivienda,
            'comodidad_espacio_familiar' => $this->comodidad_espacio_familiar,
            'numero_dormitorios' => $this->numero_dormitorios,
            'existe_hacinamiento' => $this->existe_hacinamiento,
            'existe_upc_cercano' => $this->existe_upc_cercano,
            'otras_consideraciones' => $this->otras_consideraciones,
            'imagen_croquis' => url($this->imagen_croquis),
            'telefono' => $this->telefono,
            'coordenadas' => $this->coordenadas,
            'direccion' => $this->direccion,
            'referencia' => $this->referencia,
            'servicios_basicos' => $this->servicios_basicos,
            'model_id' => $this->model_id,
            'model_type' => $this->model_type,

            'familia_acogiente' => $this->familiaAcogiente ? new ViviendaResource($this->familiaAcogiente) : null,
        ];
    }
}
