<?php

namespace App\Http\Resources\RecursosHumanos\NominaPrestamos;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class PlazoPrestamoEmpresarialResource extends JsonResource
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
            'num_cuota' => $this->num_cuota,
            'fecha_vencimiento' => $this->cambiar_fecha($this->fecha_vencimiento),
            'valor_couta' => $this->valor_cuota,
            'valor_pagado' => $this->valor_pagado,
            'valor_a_pagar' =>  $this->valor_a_pagar,
            'estado_couta' => $this->estado_couta,
        ];
        return $modelo;
    }
    private function cambiar_fecha($fecha)
    {
        $fecha_formateada = Carbon::parse($fecha)->format('d-m-Y');
        return $fecha_formateada;
    }
}
