<?php

namespace App\Http\Resources\ComprasProveedores;

use App\Models\ComprasProveedores\Proforma;
use Illuminate\Http\Resources\Json\JsonResource;

class ProformaResource extends JsonResource
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
        $detalles = Proforma::listadoProductos($this->id);
        [$subtotal,  $iva, $descuento, $total] = Proforma::obtenerSumaListado($this->id);
        $modelo = [
            'id' => $this->id,
            'solicitante' => $this->solicitante->nombres . ' ' . $this->solicitante->apellidos,
            'solicitante_id' => $this->solicitante_id,
            'autorizador' => $this->autorizador->nombres . ' ' . $this->autorizador->apellidos,
            'autorizador_id' => $this->autorizador_id,
            'autorizacion' => $this->autorizacion->nombre,
            'autorizacion_id' => $this->autorizacion_id,
            'descripcion' => $this->descripcion,
            'cliente' => $this->cliente->empresa->razon_social,
            'causa_anulacion' => $this->causa_anulacion,
            'estado' => $this->estado->nombre,
            'estado_id' => $this->estado_id,
            'estado' => $this->estado->nombre,
            'created_at' => date('Y-m-d h:i:s a', strtotime($this->created_at)),
            'forma' => $this->forma,
            'tiempo' => $this->tiempo,
            'listadoProductos' => $detalles,
            'subtotal' => $subtotal,
            'descuento' => $descuento,
            'iva' => $iva,
            'total' => $total,

        ];

        if ($controller_method == 'show') {
            $modelo['solicitante'] = $this->solicitante_id;
            $modelo['autorizador'] = $this->autorizador_id;
            $modelo['autorizacion'] = $this->autorizacion_id;
            $modelo['proveedor'] = $this->proveedor_id;
            $modelo['estado'] = $this->estado_id;
        }
        return $modelo;
    }
}
