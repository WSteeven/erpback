<?php

namespace App\Http\Resources\Medico;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

class FrPuestoTrabajoActualResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'puesto_trabajo' => $this->puesto_trabajo,
            'actividad' => $this->actividad,
            'medidas_preventivas' => $this->medidas_preventivas,
            'detalle_categ_factor_riesg_fr_puest_trab_act' => $this->detalleCategFactorRiesgoFrPuestoTrabAct->pluck('id'),
            'ficha_preocupacional' => $this->fichaPreocupacional
        ];
    }

}
