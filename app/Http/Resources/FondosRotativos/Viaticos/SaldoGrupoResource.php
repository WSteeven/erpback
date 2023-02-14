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
            'tipo_saldo'=>$this->id_tipo_saldo,
            'usuario'=>$this->id_usuario,
            'usuario_info'=>$this->usuario->name,
            'estatus_info'=>$this->estatus->descripcion,
            'tipo_fondo'=>$this->id_tipo_fondo,
            'tipo_fondo_info'=>$this->tipo_fondo->descripcion,
            'tipo_saldo_info'=>$this->tipo_saldo->descripcion,
            'id_saldo' => $this->id_saldo,
            'descripcion_saldo'=>$this->descripcion_saldo,
            'saldo_anterior'=>$this->saldo_anterior,
            'saldo_depositado' => $this->saldo_depositado,
            'saldo_actual' => $this->saldo_actual,
            'fecha_inicio' => $this->fecha_inicio,
            'fecha_fin' => $this->fecha_fin,
            'transcriptor'=>  $this->transcriptor,
            'fecha_trans'=>$this->fecha_trans,
        ];
        return $modelo;
    }
}
