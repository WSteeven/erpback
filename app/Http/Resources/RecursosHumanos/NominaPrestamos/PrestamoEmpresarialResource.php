<?php

namespace App\Http\Resources\RecursosHumanos\NominaPrestamos;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PrestamoEmpresarialResource extends JsonResource
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
            'solicitante_info' => $this->empleado_info->nombres . ' ' . $this->empleado_info->apellidos,
            'fecha' => $this->fecha,
            'monto' =>  $this->monto,
            'periodo' =>   $this->periodo_id,
            'periodo_info' => $this->periodo_info? $this->periodo_info->nombre:'' ,
            'valor_utilidad' => $this->valor_utilidad,
            'plazo' => (int) $this->plazo,
            'plazos' => $this->plazo_prestamo_empresarial_info,
            'estado' => $this->estado,
            'motivo' => $this->motivo,
            'fecha_inicio_cobro' => $this->fecha_inicio_cobro,


        ];
    }

}
