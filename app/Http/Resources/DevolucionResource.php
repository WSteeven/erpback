<?php

namespace App\Http\Resources;

use App\Models\Devolucion;
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
            'id'=>$this->id,
            'justificacion'=>$this->justificacion,
            'solicitante'=>$this->solicitante->nombres.' '. $this->solicitante->apellidos,
            'tarea'=>$this->tarea?->detalle,
            'sucursal'=>$this->sucursal->lugar,
            'estado'=>$this->estado,
            'listadoProductos'=>$detalles,
            'created_at' => date('d/m/Y', strtotime($this->created_at)),
            'updated_at' => $this->updated_at,

            'es_tarea'=>$this->tarea?true:false,
        ];

        if($controller_method=='show'){
            $modelo['solicitante']=$this->solicitante_id;
            $modelo['tarea']=$this->tarea_id;
            $modelo['sucursal']=$this->sucursal_id;
        }

        return $modelo;
    }
}
