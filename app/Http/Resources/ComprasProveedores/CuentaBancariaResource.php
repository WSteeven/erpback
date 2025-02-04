<?php

namespace App\Http\Resources\ComprasProveedores;

use Illuminate\Http\Resources\Json\JsonResource;

class CuentaBancariaResource extends JsonResource
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
            'id' => $this['id'],
            'tipo_cuenta' => $this['tipo_cuenta'],
            'numero_cuenta' => $this['numero_cuenta'],
            'banco' => $this->banco->nombre,
            'codigo_banco' => $this->banco->codigo,
        ];
    }
}
