<?php

namespace App\Http\Resources\RecursosHumanos\NominaPrestamos;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PlazoPrestamoEmpresarialResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'num_cuota' => $this->num_cuota,
            'fecha_vencimiento' => $this->fecha_vencimiento,
            'fecha_pago' => $this->fecha_vencimiento,
            'valor_cuota' => $this->valor_cuota,
            'valor_pagado' => $this->valor_pagado,
            'valor_a_pagar' =>  $this->valor_a_pagar,
            'pago_cuota' => $this->pago_cuota,
            'id_prestamo_empresarial' => $this->id_prestamo_empresarial,
            'estado' => $this->estado,
            'comentario' => $this->comentario,
            'modificada' => $this->modificada,
        ];
    }

}
