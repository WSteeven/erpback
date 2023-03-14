<?php

namespace App\Http\Resources\FondosRotativos\Gastos;

use Illuminate\Http\Resources\Json\JsonResource;

class GastoResource extends JsonResource
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
        $modelo = [
            'id' => $this->id,
            'fecha_viat' => $this->fecha_viat,
            'lugar' => $this->id_lugar,
            'num_tarea' => $this->id_tarea,
            'tarea_info' =>  $this->tarea_info !=null? $this->tarea_info->codigo_tarea.' - '. $this->tarea_info->detalle:'Sin Tarea',
            'proyecto' => $this->id_proyecto != null ? $this->id_proyecto : 0,
            'proyecto_info' => $this->proyecto_info!=null? $this->proyecto_info->codigo_proyecto.' - '.$this->proyecto_info->nombre: 'Sin Proyecto',
            'ruc' => $this->ruc,
            'factura' => $this->factura,
            'proveedor' => $this->proveedor,
            'aut_especial_user' => $this->aut_especial_user->name,
            'aut_especial' => $this->aut_especial,
            'detalle_info' => $this->detalle_info->descripcion,
            'detalle' => $this->detalle,
            'cantidad' => $this->cant,
            'valor_u' => $this->valor_u,
            'total' => $this->total,
            'comprobante1' => $this->comprobante?url($this->comprobante):null,
            'comprobante2' => $this->comprobante2?url( $this->comprobante2):null,
            'observacion' => $this->observacion,
            'id_usuario' => $this->id_usuario,
            'estado' => $this->estado,
            'estado_info' => $this->estado_info->descripcion,
            'detalle_esta' => $this->detalle,
            'estado' => $this->estado,
            'id_lugar' => $this->id_lugar,
        ];
        return $modelo;
    }
}
