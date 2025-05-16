<?php

namespace App\Http\Resources\RecursosHumanos\TrabajoSocial;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExperienciaPreviaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'ficha_id'=> $this->ficha_id,
            'empleado_id'=> $this->empleado_id,
            'nombre_empresa'=> $this->nombre_empresa,
            'cargo'=> $this->cargo,
            'antiguedad'=> $this->antiguedad,
            'asegurado_iess'=> $this->asegurado_iess,
            'telefono'=> $this->telefono,
            'fecha_retiro'=> $this->fecha_retiro,
            'motivo_retiro'=> $this->motivo_retiro,
            'salario'=> $this->salario,
        ];
    }
}
