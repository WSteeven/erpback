<?php

namespace App\Http\Resources\RecursosHumanos\ControlPersonal;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HorarioLaboralResource extends JsonResource
{
    /**
     * Transformar el recurso en un array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        $tipos_horarios = ['NORMAL', 'NOCTURNO', 'FIN DE SEMANA'];

        return [
            'id' => $this->id,
            'tipo' => in_array($this->nombre, $tipos_horarios) ? $this->nombre : 'PERSONALIZADO',
            'nombre' => $this->nombre,
            'dias' => $this->dias,
            'inicio_pausa' => $this->inicio_pausa ? Carbon::parse($this->inicio_pausa)->format('H:i') : null,
            'fin_pausa' => $this->inicio_pausa ? Carbon::parse($this->fin_pausa)->format('H:i') : null,
            'tiene_pausa' => !!($this->inicio_pausa || $this->fin_pausa),
            'activo' => $this->activo,
            'es_turno_de_noche' => $this->es_turno_de_noche,
            'hora_entrada' => $this->hora_entrada ? Carbon::parse($this->hora_entrada)->format('H:i') : null,
            'hora_salida' => $this->hora_salida ? Carbon::parse($this->hora_salida)->format('H:i') : null,
        ];
    }
}
