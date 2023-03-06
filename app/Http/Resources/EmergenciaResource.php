<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EmergenciaResource extends JsonResource
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
            'regional' => $this->regional,
            'atencion' => $this->atencion,
            'tipo_intervencion' => $this->tipo_intervencion,
            'causa_intervencion' => $this->causa_intervencion,
            'fecha_reporte_problema' => $this->fecha_reporte_problema,
            'hora_reporte_problema' => $this->hora_reporte_problema,
            'fecha_arribo' => $this->fecha_arribo,
            'hora_arribo' => $this->hora_arribo,
            'fecha_fin_reparacion' => $this->fecha_fin_reparacion,
            'hora_fin_reparacion' => $this->hora_fin_reparacion,
            'fecha_retiro_personal' => $this->fecha_retiro_personal,
            'hora_retiro_personal' => $this->hora_retiro_personal,
            'tiempo_espera_adicional' => $this->tiempo_espera_adicional,
            'estacion_referencia_afectacion' => $this->estacion_referencia_afectacion,
            'distancia_afectacion' => $this->distancia_afectacion,
            'trabajo_realizado' => $this->trabajo_realizado,
            'observaciones' => $this->observaciones,
            'materiales_ocupados' => $this->materiales_ocupados,
            'trabajo' => $this->subtarea_id,
        ];
    }
}
