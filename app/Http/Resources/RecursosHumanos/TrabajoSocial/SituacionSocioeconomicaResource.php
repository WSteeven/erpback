<?php

namespace App\Http\Resources\RecursosHumanos\TrabajoSocial;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SituacionSocioeconomicaResource extends JsonResource
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
            'ficha_id' => $this->ficha_id,
            'empleado_id' => $this->empleado_id,
            'cantidad_personas_aportan' => $this->cantidad_personas_aportan,
            'cantidad_personas_dependientes' => $this->cantidad_personas_dependientes,
            'recibe_apoyo_economico_otro_familiar' => $this->recibe_apoyo_economico_otro_familiar,
            'familiar_apoya_economicamente' => $this->familiar_apoya_economicamente,
            'recibe_apoyo_economico_gobierno' => $this->recibe_apoyo_economico_gobierno,
            'institucion_apoya_economicamente' => $this->institucion_apoya_economicamente,
            'tiene_prestamos' => $this->tiene_prestamos,
            'cantidad_prestamos' => $this->cantidad_prestamos,
            'entidad_bancaria' => $this->entidad_bancaria,
            'tiene_tarjeta_credito' => $this->tiene_tarjeta_credito,
            'cantidad_tarjetas_credito' => $this->cantidad_tarjetas_credito,
            'vehiculo' => $this->vehiculo,
            'tiene_terreno' => $this->tiene_terreno,
            'especificacion_terreno' => $this->especificacion_terreno,
            'tiene_bienes' => $this->tiene_bienes,
            'especificacion_bienes' => $this->especificacion_bienes,
            'tiene_ingresos_adicionales' => $this->tiene_ingresos_adicionales,
            'especificacion_ingresos_adicionales' => $this->especificacion_ingresos_adicionales,
            'ingresos_adicionales' => $this->ingresos_adicionales,
            'apoya_familiar_externo' => $this->apoya_familiar_externo,
            'valor_apoyo_familiar_externo' => $this->valor_apoyo_familiar_externo,
            'familiar_externo_apoyado' => $this->familiar_externo_apoyado,
        ];
    }
}
