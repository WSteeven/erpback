<?php

namespace App\Http\Resources\Tareas;

use App\Models\Empleado;
use Illuminate\Http\Resources\Json\JsonResource;

class DetalleTransferenciaProductoEmpleadoResource extends JsonResource
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
            'transferencia_id' => $this->transf_produc_emplea_id,
            'empleado_envia' => Empleado::extraerNombresApellidos(Empleado::find($this->transferencia->empleado_origen_id)),
            'empleado_recibe' => Empleado::extraerNombresApellidos(Empleado::find($this->transferencia->empleado_destino_id)),
            'detalle_id' => $this->detalle_producto_id,
            'descripcion' => $this->detalleProducto->descripcion,
            'producto' => $this->detalleProducto->producto->nombre,
            'cantidad' => $this->cantidad,
            'cliente_id' => $this->cliente_id,
            'serial' => $this->detalleProducto->serial,
            'punta_inicial' => $this->detalleProducto->punta_inicial,
            'punta_final' => $this->detalleProducto->punta_final,
            'unidad_medida' => $this->detalleProducto->producto->unidadMedida->nombre,
            'created_at' => date('d/m/Y', strtotime($this->created_at)),
        ];
    }
}
