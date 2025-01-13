<?php

namespace App\Http\Resources\RecursosHumanos\TrabajoSocial;

use App\Http\Resources\TrabajoSocial\FamiliaAcogienteResource;
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
            'numero_plantas' => $this->numero_plantas,
            'material_techo' => $this->material_techo,
            'material_piso' => $this->material_piso,
            'distribucion_vivienda' => $this->distribucion_vivienda,
            'comodidad_espacio_familiar' => $this->comodidad_espacio_familiar,
            'numero_dormitorios' => $this->numero_dormitorios,
            'numero_personas' => $this->numero_personas,
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

            'amenaza_inundacion' => $this->amenaza_inundacion,
            'amenaza_deslaves' => $this->amenaza_deslaves,
            'otras_amenazas_previstas' => $this->otras_amenazas_previstas,
            'otras_amenazas' => $this->otras_amenazas,
            'existe_peligro_tsunami' => $this->existe_peligro_tsunami,
            'existe_peligro_lahares' => $this->existe_peligro_lahares,

            'tiene_donde_evacuar' => $this->tiene_donde_evacuar,
            'familia_acogiente' => $this->familiaAcogiente ? new FamiliaAcogienteResource($this->familiaAcogiente) : null,
        ];
    }
}
