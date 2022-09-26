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
        //[$nom, $obs]  = TransaccionesBodega::obtenerUltimaAutorizacion($this->id, $this->autorizacion_id);
        return [
            //'autorizacion'=>$nom,
            //'obs_autorizacion'=>$obs,
            'justificacion'=>$this->justificacion,
            'fecha_limite'=>$this->fecha_limite,
            //'estado_id'=>$this->estados()->id,
            'solicitante'=>$this->solicitante->nombres.' '.$this->solicitante->apellidos,
            //'solicitante_id'=>$this->solicitante->name,
            // 'tipo_id'=>$this->tipoTransaccion()->id,
            'subtipo'=>$this->subtipo->nombre,
            'sucursal'=>$this->sucursal->lugar,
            //'per_autoriza_id'=>$this->solicitante()->empleados()->jefe_id,
            'autoriza'=>$this->autoriza->nombres.' '.$this->autoriza->apellidos,
            'lugar_destino'=>$this->lugar_destino,
            'atiende'=>$this->atiende->nombres.' '.$this->atiende->apellidos,
        ];
    }
}
