<?php

namespace App\Http\Resources\SSO;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed $incidente
 * @property mixed $pedido
 */
class SeguimientoIncidenteResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this['id'],
            'incidente' => $this['incidente_id'],
            'empleado' => $this->incidente->empleadoReporta,
            'pedido' => $this['pedido_id'],
            'devolucion_id' => $this['devolucion_id'],
            'solicitud_descuento' => $this['solicitud_descuento_id'],
            'causa_raiz' => $this['causa_raiz'] ?? '',
            'acciones_correctivas' => $this['acciones_correctivas'] ?? '',
        ];
    }
}
