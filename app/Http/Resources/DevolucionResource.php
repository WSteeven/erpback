<?php

namespace App\Http\Resources;

use App\Models\Devolucion;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;

class DevolucionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        $controller_method = $request->route()->getActionMethod();
        $detalles = Devolucion::listadoProductos($this->id);
        $modelo = [
            'id' => $this->id,
            'justificacion' => $this->justificacion,
            'solicitante' => $this->solicitante->nombres . ' ' . $this->solicitante->apellidos,
            'solicitante_id' => $this->solicitante_id,
            'tarea' => $this->tarea?->titulo,
            'tarea_id' => $this->tarea_id,
            'canton' => $this->canton?->canton,
            'estado' => $this->estado,
            'observacion_aut' => $this->observacion_aut,
            'autorizacion' => $this->autorizacion?->nombre,
            'per_autoriza' => $this->autoriza?->nombres . ' ' . $this->autoriza?->apellidos,
            'per_autoriza_id' => $this->per_autoriza_id,
            'estado_bodega' => $this->estado_bodega,
            'es_para_stock' => $this->stock_personal,
            'listadoProductos' => $detalles,
            'created_at' => date('Y-m-d', strtotime($this->created_at)),
            'updated_at' => $this->updated_at,

            'es_tarea' => $this->tarea ? true : false,
            'tiene_observacion_aut' => $this->observacion_aut ? true : false,
            'cliente' => $this->cliente_id,
            'cliente_id' => $this->cliente_id,
            'sucursal' => $this->sucursal?->lugar,
            'sucursal_id' => $this->sucursal_id,
            'pedido_automatico' => $this->pedido_automatico,
        ];

        if ($controller_method == 'show') {
            $modelo['solicitante'] = $this->solicitante_id;
            $modelo['tarea'] = $this->tarea_id;
            $modelo['canton'] = $this->canton_id;
            $modelo['per_autoriza'] = $this->per_autoriza_id;
            $modelo['autorizacion'] = $this->autorizacion_id;
            $modelo['sucursal'] = $this->sucursal_id;
            $modelo['cliente'] = $this->cliente_id;
            $modelo['misma_condicion'] = Devolucion::obtenerCondicionListado($this->id);
        }

        return $modelo;
    }
}
