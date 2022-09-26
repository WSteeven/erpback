<?php

namespace App\Http\Resources;

use App\Models\TransaccionesBodega;
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
        //[$nom, $obs]  = TransaccionesBodega::obtenerUltimaAutorizacion($this->id, $this->autorizacion_id);
        return [
            //'autorizacion'=>$nom,
            //'obs_autorizacion'=>$obs,
            'justificacion'=>$this->justificacion,
            'fecha_limite'=>$this->fecha_limite,
            //'estado_id'=>$this->estados()->id,
            //'solicitante_id'=>$this->solicitante()->id,
            'solicitante_id'=>$this->solicitante->name,
            //'tipo_id'=>$this->tipoTransaccion()->id,
            'tipo_id'=>$this->subtipo_id,
            //'sucursal_id'=>$this->solicitante()->empleados()->sucursal_id,
            //'per_autoriza_id'=>$this->solicitante()->empleados()->jefe_id,
            'per_entrega_id'=>$this->per_entrega_id,
            'lugar_destino'=>$this->lugar_destino
        ];
    }
}
