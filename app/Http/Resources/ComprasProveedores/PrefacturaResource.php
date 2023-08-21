<?php

namespace App\Http\Resources\ComprasProveedores;

use App\Models\ComprasProveedores\Prefactura;
use Illuminate\Http\Resources\Json\JsonResource;

class PrefacturaResource extends JsonResource
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
        $detalles = Prefactura::listadoProductos($this->id);
        [$subtotal,  $iva, $descuento, $total] = Prefactura::obtenerSumaListado($this->id);
        $modelo = [
            'id' => $this->id,
            'codigo' => $this->codigo,
            'solicitante' => $this->solicitante->nombres . ' ' . $this->solicitante->apellidos,
            'solicitante_id' => $this->solicitante_id,
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
            'iva' => $this->iva,
            'sum_subtotal' => number_format($subtotal, 2),
            'sum_descuento' => number_format($descuento, 2),
            'sum_iva' => number_format($iva, 2),
            'sum_total' => number_format($total, 2),

        ];

        if ($controller_method == 'show') {
            $modelo['solicitante'] = $this->solicitante_id;
            $modelo['cliente'] = $this->cliente_id;
            $modelo['estado'] = $this->estado_id;
        }
        return $modelo;
    }
}
