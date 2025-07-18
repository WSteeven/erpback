<?php

namespace App\Http\Resources\ActivosFijos;

use App\Models\Empleado;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EntregaActivoFijoResource extends JsonResource
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
            'id' => $this['id'],
            'fecha_hora_entrega' => $this['fecha_hora'],
            'cantidad' => $this['cantidad'],
            'condicion' => $this['condicion'],
            'sucursal' => $this['sucursal'],
            'responsable' => Empleado::extraerNombresApellidos(Empleado::find($this['responsable_id'])),
            'num_transaccion' => $this['num_transaccion'],
            'estado_comprobante' => $this['estado_comprobante'],
            'codigo_permiso_traslado' => $this['codigo_permiso_traslado'],
        ];
    }
}
