<?php

namespace App\Http\Resources\RecursosHumanos\TrabajoSocial;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SaludResource extends JsonResource
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
            'tiene_discapacidad' => !!$this->discapacidades,
            'discapacidades' => is_null($this->discapacidades) ? [] : $this->discapacidades,
            'tiene_familiar_dependiente_discapacitado' => !!$this->discapacidades_familiar_dependiente,
            'discapacidades_familiar_dependiente' => is_null($this->discapacidades_familiar_dependiente) ? [] : $this->discapacidades_familiar_dependiente,
            'tiene_enfermedad_cronica' => !!$this->enfermedad_cronica,
            'enfermedad_cronica' => $this->enfermedad_cronica,
            'alergias' => $this->alergias,
            'lugar_atencion' => $this->lugar_atencion,
            'nombre_familiar_dependiente_discapacitado' => $this->nombre_familiar_dependiente_discapacitado,
            'parentesco_familiar_discapacitado' => $this->parentesco_familiar_discapacitado,
            'model_id' => $this->model_id,
            'model_type' => $this->model_type,
        ];
    }
}
