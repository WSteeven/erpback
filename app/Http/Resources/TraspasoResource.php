<?php

namespace App\Http\Resources;

use App\Models\Traspaso;
use Illuminate\Http\Resources\Json\JsonResource;

class TraspasoResource extends JsonResource
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
        $detalles = Traspaso::listadoProductos($this->id);
        $modelo = [
            'id'=>$this->id,
            'justificacion' => $this->justificacion,
            'devuelta' => $this->devuelta,
            'solicitante' => $this->solicitante->nombres.' '. $this->solicitante->apellidos,
            'desde_cliente' => $this->prestamista->empresa->razon_social,
            'hasta_cliente' => $this->prestatario->empresa->razon_social,
            'tarea' => $this->tarea?->detalle,
            'sucursal' => $this->sucursal->lugar,
            'estado' => $this->estado->nombre,
            'listadoProductos.*.cantidades' => $detalles,
            'created_at' => date('d/m/Y', strtotime($this->created_at)),

            'es_tarea'=>$this->tarea?true:false,
        ];

        if($controller_method=='show'){
            $modelo['solicitante']=$this->solicitante_id;
            $modelo['desde_cliente']=$this->desde_cliente_id;
            $modelo['hasta_cliente']=$this->hasta_cliente_id;
            $modelo['sucursal']=$this->sucursal_id;
            $modelo['estado']=$this->estado_id;
            $modelo['tarea']=$this->tarea_id;
        }

        return $modelo;
    }
}
