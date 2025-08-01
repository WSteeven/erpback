<?php

namespace App\Http\Resources\RecursosHumanos\NominaPrestamos;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SolicitudPrestamoEmpresarialResource extends JsonResource
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
            'id' => $this->id,
            'solicitante' => $this->solicitante,
            'solicitante_info' => $this->empleado_info!=null?$this->empleado_info->nombres . ' ' . $this->empleado_info->apellidos:'',
            'fecha' =>  $this->fecha,
//            'fecha' =>  $controller_method === 'show'?$this->fecha:$this->cambiar_fecha($this->fecha),
            'monto' =>  $this->monto,
            'plazo' => $this->plazo,
            'motivo' =>  $this->motivo,
            'observacion' => $this->observacion,
            'cargo_utilidad' => $this->periodo_id != null,
            'periodo' =>   $this->periodo_id,
            'periodo_info' => $this->periodo_info? $this->periodo_info->nombre:'' ,
            'valor_utilidad' => $this->valor_utilidad,
            'foto' =>  $this->foto?url($this->foto):null,
            'estado' => $this->estado,
            'gestionada' => $this->gestionada,
            'estado_info' =>  $this->estado_info!=null ? $this->estado_info->nombre:'',
        ];
    }

}
