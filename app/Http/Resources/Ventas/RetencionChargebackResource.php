<?php

namespace App\Http\Resources\Ventas;

use Illuminate\Http\Resources\Json\JsonResource;

class RetencionChargebackResource extends JsonResource
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
            'venta_id' => $this->venta_id,
            'venta_info' => $this->venta->orden_id,
            'vendedor_id' => $this->vendedor_id,
            'vendedor_info' => $this->vendedor->empleado->nombres . ' ' . $this->vendedor->empleado->apellidos,
            'fecha_retencion' => $this->fecha_retencion,
            'valor_retenido' => $this->valor_retenido,
            'pagado' => $this->pagado,
        ];
    }
}
