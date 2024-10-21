<?php

namespace App\Http\Resources\ActivosFijos;

use App\Http\Resources\ArchivoResource;
use App\Models\Empleado;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class SeguimientoConsumoActivosFijosResource extends JsonResource
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
            'created_at' => Carbon::parse($this->created_at)->format('Y-m-d H:i:s'),
            'cantidad_utilizada' => $this->cantidad_utilizada,
            'detalle_producto' => $this->detalleProducto->descripcion,
            'detalle_producto_id' => $this->detalle_producto_id,
            'cliente_id' => $this->cliente_id,
            'serie' => $this->detalleProducto->serial,
            'canton' => $this->canton->canton,
            'categoria_motivo_consumo' => $this->motivoConsumoActivoFijo->categoriaMotivoConsumoActivoFijo?->nombre,
            'motivo_consumo' => $this->motivoConsumoActivoFijo?->nombre,
            'observacion' => $this->observacion,
            'empleado' => Empleado::extraerNombresApellidos($this->empleado),
            'se_reporto_sicosep' => $this->se_reporto_sicosep,
            'archivos' => ArchivoResource::collection($this->archivos),
        ];
    }
}
