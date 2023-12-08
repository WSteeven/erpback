<?php

namespace App\Http\Resources\RecursosHumanos\NominaPrestamos;

use Illuminate\Http\Resources\Json\JsonResource;

class ExtensionCoverturaSaludResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $modelo = [
            'id' => $this->id,
            'mes' =>$this->mes,
            'dependiente_' =>$this->dependiente,
            'dependiente_info' => $this->dependiente_info != null ? $this->dependiente_info->nombres . ' ' . $this->dependiente_info->apellidos : '',
            'empleado' =>$this->empleado_id,
            'empleado_info' => $this->empleado_info != null ? $this->empleado_info->nombres . ' ' . $this->empleado_info->apellidos : '',
            'origen' =>$this->origen,
            'materia_grabada' =>$this->materia_grabada,
            'aporte' => $this->aporte,
            'aporte_porcentaje' => $this->aporte_porcentaje ,
            'aprobado'=> $this->aprobado,
            'observacion'=> $this->observacion,

        ];
        return $modelo;
    }
}
