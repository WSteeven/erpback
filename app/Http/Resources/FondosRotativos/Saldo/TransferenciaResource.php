<?php

namespace App\Http\Resources\FondosRotativos\Saldo;

use App\Models\FondosRotativos\Gasto\Gasto;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class TransferenciaResource extends JsonResource
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
            'id' => $this->id,
            'usuario_envia_info' => $this->usuario_envia->name,
            'usuario_recibe_info' => $this->usuario_recibe == null? 'JPConstructred':$this->usuario_recibe->name,
            'usuario_recibe' => $this->usuario_recibe_id,
            'usuario_envia_id' => $this->usuario_envia_id,
            'usuario_recive_id' => $this->usuario_recibe_id,
            'cuenta' => $this->cuenta,
            'tarea' => $this->id_tarea,
            'monto' => $this->monto,
            'motivo' => $this->motivo,
            'comprobante' => $this->comprobante,
        ];
        return $modelo;
    }

}
