<?php

namespace App\Http\Resources\ComprasProveedores;

use App\Models\ComprasProveedores\OrdenCompra;
use Illuminate\Http\Resources\Json\JsonResource;

class OrdenCompraResource extends JsonResource
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
        $detalles = OrdenCompra::listadoProductos($this->id);
        $modelo = [
            'id' => $this->id,
            'solicitante' => $this->solicitante->nombres . ' ' . $this->solicitante->apellidos,
            'solicitante_id' => $this->solicitante_id,
            'pedido' => $this->pedido_id,
            'autorizador' => $this->autorizador->nombres . ' ' . $this->autorizador->apellidos,
            'autorizador_id' => $this->autorizador_id,
            'autorizacion' => $this->autorizacion->nombre,
            'autorizacion_id' => $this->autorizacion_id,
            'descripcion' => $this->descripcion,
            'listadoProductos' => $detalles,
            'estado' => $this->estado->nombre,
            'estado_id' => $this->estado_id,
            'estado' => $this->estado,
            'created_at' => date('Y-m-d h:i:s a', strtotime($this->created_at)),

        ];
        return $modelo;
    }
}
