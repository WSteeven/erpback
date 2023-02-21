<?php

namespace App\Http\Resources\FondosRotativos\Viaticos;

use Illuminate\Http\Resources\Json\JsonResource;

class SubDetalleViaticoResource extends JsonResource
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
            'id'=>$this->id,
            'descripcion'=>$this->descripcion,
            'id_detalle_viatico' => $this->id_detalle_viatico,
            'detalle_viatico'=> $this->detalle->descripcion,
            'autorizacion'=>$this->autorizacion,
            'id_estatus' => $this->id_estatus,
            'estatus' => $this->estatus->descripcion,
            'transcriptor'=>  $this->transcriptor,
            'fecha_trans'=>$this->fecha_trans,
        ];
        return $modelo;
    }
}
