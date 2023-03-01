<?php

namespace App\Http\Resources;

use App\Models\Pedido;
use Illuminate\Http\Resources\Json\JsonResource;

class PedidoResource extends JsonResource
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
        $detalles = Pedido::listadoProductos($this->id);

        $modelo = [
            'id' => $this->id,
            'justificacion' => $this->justificacion,
            'fecha_limite' => is_null($this->fecha_limite)?'':date('d-m-Y', strtotime($this->fecha_limite)),
            'observacion_aut' => $this->observacion_aut,
            'observacion_est' => $this->observacion_est,
            'solicitante' => $this->solicitante->nombres . ' ' . $this->solicitante->apellidos,
            'solicitante_id' => $this->solicitante_id,
            'responsable' => is_null($this->responsable)?'':$this->responsable->nombres.' '.$this->responsable->apellidos,
            'responsable_id' => $this->responsable_id,
            'autorizacion' => $this->autorizacion->nombre,
            'per_autoriza' => $this->autoriza->nombres . ' ' . $this->autoriza->apellidos,
            'per_autoriza_id' => $this->per_autoriza_id,
            'tarea' => $this->tarea?->titulo,
            'sucursal' => $this->sucursal->lugar,
            'sucursal_id' => $this->sucursal_id,
            'estado' => $this->estado->nombre,
            'listadoProductos' => $detalles,
            'created_at' => date('d-m-Y', strtotime($this->created_at)),
            'updated_at' => $this->updated_at,

            'tiene_fecha_limite'=>$this->fecha_limite?true:false,
            'es_tarea' => $this->tarea ? true : false,
            'tiene_observacion_aut' => $this->observacion_aut ? true : false,
            'tiene_observacion_est' => $this->observacion_est ? true : false,
        ];

        if ($controller_method == 'show') {
            $modelo['solicitante'] = $this->solicitante_id;
            $modelo['responsable'] = $this->responsable_id;
            $modelo['autorizacion'] = $this->autorizacion_id;
            $modelo['per_autoriza'] = $this->per_autoriza_id;
            $modelo['tarea'] = $this->tarea_id;
            $modelo['sucursal'] = $this->sucursal_id;
            $modelo['estado'] = $this->estado_id;
        }



        return $modelo;
    }
}
