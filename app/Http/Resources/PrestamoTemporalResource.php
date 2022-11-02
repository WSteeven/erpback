<?php

namespace App\Http\Resources;

use App\Models\PrestamoTemporal;
use Illuminate\Http\Resources\Json\JsonResource;

class PrestamoTemporalResource extends JsonResource
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
        $detalles = PrestamoTemporal::listadoProductos($this->id);

        $modelo =[
            'id'=>$this->id,
            'fecha_salida'=>$this->fecha_salida,
            'fecha_devolucion'=>$this->fecha_devolucion,
            'observacion'=> $this->observacion,
            'solicitante'=>$this->solicitante ? $this->solicitante->nombres . ' ' . $this->solicitante->apellidos : 'N/A',
            'per_entrega'=> $this->entrega->nombres.' '.$this->entrega->apellidos,
            'per_entrega_recibe'=>$this->recibe? $this->entrega->nombres.' '.$this->entrega->apellidos.' / '.$this->recibe->nombres.' '.$this->recibe->apellidos:$this->entrega->nombres.' '.$this->entrega->apellidos,
            'per_recibe'=> $this->recibe ? $this->recibe->nombres . ' ' . $this->recibe->apellidos : null,
            'estado'=>$this->estado,
        ];
        if($controller_method=='show'){
            $modelo['solicitante']=$this->solicitante_id;
            $modelo['per_entrega']=$this->per_entrega_id;
            $modelo['per_recibe']=$this->per_recibe_id;
            $modelo['listadoProductos']=$detalles;
        }

        return $modelo;
    }
}
