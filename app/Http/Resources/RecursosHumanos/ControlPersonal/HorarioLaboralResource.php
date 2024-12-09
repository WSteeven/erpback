<?php

namespace App\Http\Resources\RecursosHumanos\ControlPersonal;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class HorarioLaboralResource extends JsonResource
{
    /**
     * Transformar el recurso en un array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'hora_entrada' => $this->hora_entrada ? Carbon::parse($this->hora_entrada)->format('H:i') : null,
            'hora_salida' => $this->hora_salida ? Carbon::parse($this->hora_salida)->format('H:i') : null,
        ];
    }
}
