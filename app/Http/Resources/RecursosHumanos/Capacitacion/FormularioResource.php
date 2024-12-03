<?php

namespace App\Http\Resources\RecursosHumanos\Capacitacion;

use App\Models\Empleado;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FormularioResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return  [
            'id'=>$this->id,
            'empleado_id'=>$this->empleado_id,
            'empleado'=>Empleado::extraerNombresApellidos($this->empleado),
            'nombre'=>$this->nombre,
            'formulario'=>$this->formulario,
            'es_recurrente'=>$this->es_recurrente,
            'periodo_recurrencia'=>$this->periodo_recurrencia, //expresado en meses
            'fecha_inicio'=>$this->fecha_inicio,
            'tipo'=>$this->tipo, //interna,externa
            'activo'=>$this->activo,
        ];

    }
}
