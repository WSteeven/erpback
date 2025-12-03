<?php

namespace App\Http\Resources\ControlPersonal;

use App\Models\Empleado;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Src\Shared\Utils;

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
            'empleado_id' => $this->empleado_id,
            'empleado' => Empleado::extraerNombresApellidos($this->empleado),
            'fecha' => $this->fecha,
            'marcaciones' => collect($this->marcaciones)->map(function ($marcacion) {
                foreach ($marcacion as $key=>$hora) {
                    return trim($hora) ? "<b>$key</b>: $hora":null;
                }
            })->filter()->implode(', '),
        ];
    }
}
