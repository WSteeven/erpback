<?php

namespace App\Http\Resources\FondosRotativos\Viaticos;

use Illuminate\Http\Resources\Json\JsonResource;

class SaldoGrupoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $controller_method = $request->route()->getActionMethod();
        $modelo = [
            'id'=>$this->id,
            'fecha'=>$this->fecha,
            'tipo_saldo'=>$this->tipo_saldo,
            'id_estatus' => $this->id_estatus,
            'transcriptor'=>  $this->transcriptor,
            'fecha_trans'=>$this->fecha_trans,
            'tipo_saldo'=>$this->tipo_saldo,
            'id_estatus' => $this->id_estatus,
            'transcriptor'=>  $this->transcriptor,
            'fecha_trans'=>$this->fecha_trans,
        ];
        return $modelo;
    }
}
