<?php

namespace App\Http\Resources\ComprasProveedores;

use App\Models\ComprasProveedores\PreordenCompra;
use Illuminate\Http\Resources\Json\JsonResource;

class PreordenCompraResource extends JsonResource
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
        $detalles = PreordenCompra::listadoProductos($this->id);
        $modelo = [
            'id' => $this->id,
            'solicitante' => $this->solicitante->nombres . ' ' . $this->solicitante->apellidos,
            'solicitante_id' => $this->solicitante_id,
            'pedido' => $this->pedido_id,
            'autorizador' => $this->autorizador->nombres . ' ' . $this->autorizador->apellidos,
            'autorizador_id' => $this->autorizador_id,
            'autorizacion' => $this->autorizacion->nombre,
            'autorizacion_id' => $this->autorizacion_id,
            'justificacion' => $this->pedido? $this->pedido->justificacion:'Generada automaticamente por una consolidación de ítems y cantidades',
            'listadoProductos' => $detalles,
            'estado' => $this->estado,
            'created_at' => date('Y-m-d h:i:s a', strtotime($this->created_at)),
        ];
        if ($controller_method == 'show') {
            $modelo['solicitante'] = $this->solicitante_id;
            $modelo['autorizador'] = $this->autorizador_id;
            $modelo['autorizacion'] = $this->autorizacion_id;
        }

        return $modelo;
    }
}
