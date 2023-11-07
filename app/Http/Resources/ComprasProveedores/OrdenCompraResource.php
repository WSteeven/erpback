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
        [$subtotal,  $iva, $descuento, $total] = OrdenCompra::obtenerSumaListado($this->id);
        $modelo = [
            'id' => $this->id,
            'codigo' => $this->codigo,
            'solicitante' => $this->solicitante->nombres . ' ' . $this->solicitante->apellidos,
            'solicitante_id' => $this->solicitante_id,
            'preorden' => $this->preorden_id,
            'pedido' => $this->pedido_id,
            'tarea' => $this->tarea?->titulo,
            'tarea_id' => $this->tarea_id,
            'autorizador' => $this->autorizador->nombres . ' ' . $this->autorizador->apellidos,
            'autorizador_id' => $this->autorizador_id,
            'autorizacion' => $this->autorizacion->nombre,
            'autorizacion_id' => $this->autorizacion_id,
            'descripcion' => $this->descripcion,
            'proveedor' => $this->proveedor?->empresa->razon_social,
            'causa_anulacion' => $this->causa_anulacion,
            'estado' => $this->estado?->nombre,
            'estado_id' => $this->estado_id,
            'created_at' => date('Y-m-d h:i:s a', strtotime($this->created_at)),
            'forma' => $this->forma,
            'tiempo' => $this->tiempo,
            'fecha' => $this->fecha,
            'listadoProductos' => $detalles,
            'iva' => $this->iva,
            'sum_subtotal' => number_format($subtotal, 2),
            'sum_descuento' => number_format($descuento, 2),
            'sum_iva' => number_format($iva, 2),
            'sum_total' => number_format($total, 2),
            'realizada' => $this->realizada,
            'observacion_realizada' => $this->observacion_realizada,
            'pagada' => $this->pagada,

        ];

        if ($controller_method == 'show') {
            $modelo['solicitante'] = $this->solicitante_id;
            $modelo['autorizador'] = $this->autorizador_id;
            $modelo['autorizacion'] = $this->autorizacion_id;
            $modelo['proveedor'] = $this->proveedor_id;
            $modelo['estado'] = $this->estado_id;
            $modelo['tarea'] = $this->tarea_id;
        }
        return $modelo;
    }
}
