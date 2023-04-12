<?php

namespace App\Http\Resources\FondosRotativos\Gastos;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class GastoVehiculoResource extends JsonResource
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
            'id_gasto' => $this->id_gasto,
            'gasto_info' => $this->gasto_info,
            'placa' => $this->placa,
            'valor_u' => $this->kilometraje,
        ];
        return $modelo;
    }
}
