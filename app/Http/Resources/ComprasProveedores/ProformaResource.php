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
        [$subtotal, $subtotal_con_impuestos, $subtotal_sin_impuestos, $iva, $descuento, $total] = Proforma::obtenerSumaListado($this->id);
        $modelo = [
            'id' => $this->id,
            'codigo' => $this->codigo,
            'solicitante' => $this->solicitante->nombres . ' ' . $this->solicitante->apellidos,
            'solicitante_id' => $this->solicitante_id,
            'autorizador' => $this->autorizador->nombres . ' ' . $this->autorizador->apellidos,
            'autorizador_id' => $this->autorizador_id,
            'autorizacion' => $this->autorizacion->nombre,
            'autorizacion_id' => $this->autorizacion_id,
            'descuento_general' => $this->descuento_general,
            'observacion_aut' => $this->observacion_aut,
            'descripcion' => $this->descripcion,
            'cliente' => $this->cliente->empresa->razon_social,
            'cliente_id' => $this->cliente_id,
            'causa_anulacion' => $this->causa_anulacion,
            'estado' => $this->estado->nombre,
            'estado_id' => $this->estado_id,
            'created_at' => date('Y-m-d h:i:s a', strtotime($this->created_at)),
            'forma' => $this->forma,
            'tiempo' => $this->tiempo,
            'listadoProductos' => $detalles,
            'iva' => $this->iva,
            'sum_subtotal' => number_format($subtotal, 2),
            'sum_subtotal_sin_impuestos' => number_format($subtotal_sin_impuestos, 2),
            'sum_subtotal_con_impuestos' => number_format($subtotal_con_impuestos, 2),
            'sum_descuento' => number_format($descuento, 2),
            'sum_iva' => number_format($iva, 2),
            'sum_total' => number_format($total - $this->descuento_general, 2),
        ];

        if ($controller_method == 'show') {
            $modelo['solicitante'] = $this->solicitante_id;
            $modelo['autorizador'] = $this->autorizador_id;
            $modelo['autorizacion'] = $this->autorizacion_id;
            $modelo['cliente'] = $this->cliente_id;
            $modelo['estado'] = $this->estado_id;
        }
        return $modelo;
    }
}
