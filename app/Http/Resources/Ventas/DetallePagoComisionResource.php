<?php

namespace App\Http\Resources\Ventas;

use Illuminate\Http\Resources\Json\JsonResource;

class DetallePagoComisionResource extends JsonResource
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
            'id' => $this->id,
            'fecha_inicio' => $this->fecha_inicio,
            'fecha_fin' => $this->fecha_fin,
            'corte_id' => $this->corte_id,
            'corte_info' => $this->corte->nombre,
            'vendedor_id' => $this->vendedor_id,
            'vendedor_info' => $this->vendedor->empleado->nombres . ' ' . $this->vendedor->empleado->apellidos,
            'chargeback' => $this->chargeback,
            'ventas' => $this->ventas,
            'valor' => $this->valor,
            'pagado' => $this->pagado,
        ];
    }
}
