<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ActivoFijoResource extends JsonResource
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
            'cantidad'=>$this->cantidad,
            'fecha_desde'=>$this->fecha_desde,
            'accion'=>$this->accion,
            'observacion'=>$this->observacion,
            'lugar'=>$this->lugar,
            'producto'=>$this->detalle->producto->id,
            'detalle_id'=>$this->detalle->descripcion,
            'empleado'=>$this->empleado->nombres.' '.$this->empleado->apellidos,
            'sucursal'=>$this->sucursal->lugar,
            'condicion'=>$this->condicion->nombre,
        ];

        if($controller_method=='show'){
            $modelo['empleado']= $this->empleado_id;
            $modelo['detalle_id']= $this->detalle_id;
            $modelo['sucursal']= $this->sucursal_id;
            $modelo['condicion']= $this->condicion_id;
        }
        return $modelo;
    }
}
