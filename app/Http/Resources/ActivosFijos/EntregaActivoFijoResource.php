<?php

namespace App\Http\Resources\ActivosFijos;

use App\Models\Empleado;
use Illuminate\Http\Resources\Json\JsonResource;

class EntregaActivoFijoResource extends JsonResource
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
            'fecha_hora_entrega' => $this['fecha_hora'],
            'cantidad' => $this['cantidad'],
            'condicion' => $this['condicion'],
            'ciudad' => $this['sucursal'],
            'responsable' => Empleado::extraerNombresApellidos(Empleado::find($this['responsable_id'])),
            'num_transaccion' => $this['num_transaccion'],
        ];
    }
}
