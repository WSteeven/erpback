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
            'autorizacion_id'=>$this->autorizacion_id,
            'observacion'=>$this->observacion,
            'fecha_limite'=>$this->fecha_limite,
            'estado_id'=>$this->estado_id,
            'solicitante_id'=>$this->solicitante_id,
            'tipo_id'=>$this->tipo_id,
            'sucursal_id'=>$this->sucursal_id,
            'per_autoriza_id'=>$this->per_autoriza_id,
            'per_entrega_id'=>$this->per_entrega_id,
            'lugar_destino'=>$this->lugar_destino
        ];
    }
}
