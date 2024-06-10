<?php

namespace App\Http\Resources\Medico;

use App\Models\Medico\EsquemaVacuna;
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
            // 'id' => $this->id,
            'dosis_aplicadas' => $this->dosis_aplicadas,
            // 'dosis_aplicadas' => EsquemaVacuna::where('paciente_id', $this->paciente_id)->where('tipo_vacuna_id', $this->tipo_vacuna_id)->count(),
            'dosis_totales' => $this->tipoVacuna?->dosis_totales,
            'observacion' => $this->observacion,
            'tipo_vacuna' => $this->tipoVacuna?->nombre,
            'tipo_vacuna_id' => $this->tipo_vacuna_id,
            'fecha' => $this->fecha,
            'lote' => $this->lote,
            'responsable_vacunacion' => $this->responsable_vacunacion,
            'establecimiento_salud' => $this->establecimiento_salud,
        ];

        if ($controller_method == 'show') {
            $modelo['tipo_vacuna'] = $this->tipo_vacuna_id;
        }

        return $modelo;
    }
}
