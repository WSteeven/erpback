<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TransaccionBodegaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'autorizacion_id'=>$this->autorizaciones()->id,
            'justificacion'=>$this->justificacion,
            'fecha_limite'=>$this->fecha_limite,
            'estado_id'=>$this->estados()->id,
            'solicitante_id'=>$this->solicitante()->id,
            'tipo_id'=>$this->tipoTransaccion()->id,
            'sucursal_id'=>$this->solicitante()->empleados()->sucursal_id,
            'per_autoriza_id'=>$this->solicitante()->empleados()->jefe_id,
            'per_entrega_id'=>$this->solicitante()->id,
            'lugar_destino'=>$this->lugar_destino
        ];
    }
}
