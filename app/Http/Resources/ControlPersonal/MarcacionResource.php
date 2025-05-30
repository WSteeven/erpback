<?php

namespace App\Http\Resources\ControlPersonal;

use App\Models\Empleado;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MarcacionResource extends JsonResource
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
            'id' => $this->id,
            'empleado' => Empleado::extraerNombresApellidos($this->empleado),
            'fecha' => $this->fecha,
            'marcaciones' => is_array($this->marcaciones)
                ? implode(' - ', $this->marcaciones)
                : $this->marcaciones,
        ];
    }
}
