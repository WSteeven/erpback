<?php

namespace App\Http\Resources;

use App\Models\Empleado;
use App\Models\Pedido;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed $id
 */
class PedidoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
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
            'solicitante' => Empleado::extraerNombresApellidos($this->solicitante),
            'solicitante_id' => $this->solicitante_id,
            'responsable' => is_null($this->responsable)?'':$this->responsable->nombres.' '.$this->responsable->apellidos,
            'responsable_id' => $this->responsable_id,
            'autorizacion' => $this->autorizacion->nombre,
            'per_autoriza' => Empleado::extraerNombresApellidos($this->autoriza),
            'per_autoriza_id' => $this->per_autoriza_id,
            'per_retira' => is_null($this->per_retira_id)?'':$this->retira->nombres.' '.$this->retira->apellidos,
            'per_retira_id' => $this->per_retira_id,
            'cliente' => $this->cliente?->empresa?->razon_social,
            'cliente_id' => $this->cliente_id,
            'tarea' => $this->tarea?->titulo,
            'tarea_id' => $this->tarea_id,
            'sucursal' => $this->sucursal->lugar,
            'sucursal_id' => $this->sucursal_id,
            'estado' => $this->estado->nombre,
            'listadoProductos' => $detalles,
            'created_at' => date('d-m-Y', strtotime($this->created_at)),
            'updated_at' => $this->updated_at,
            'evidencia1'=>$this->evidencia1 ? url($this->evidencia1) : null,
            'evidencia2'=>$this->evidencia2 ? url($this->evidencia2) : null,
            'proyecto' => $this->proyecto_id,
            'etapa' => $this->etapa_id,

            'tiene_fecha_limite'=> (bool)$this->fecha_limite,
            'es_tarea' => (bool)$this->tarea,
            'tiene_observacion_aut' => (bool)$this->observacion_aut,
            'tiene_observacion_est' => (bool)$this->observacion_est,
            'retira_tercero' => (bool)$this->per_retira_id,
            'tiene_evidencia' => $this->evidencia1 ||$this->evidencia2,
            'para_cliente' => (bool)$this->cliente,
            'estado_orden_compra'=> $this->estadoOC($this->id)
        ];

        if ($controller_method == 'show') {
            $modelo['solicitante'] = $this->solicitante_id;
            $modelo['responsable'] = $this->responsable_id;
            $modelo['autorizacion'] = $this->autorizacion_id;
            $modelo['per_autoriza'] = $this->per_autoriza_id;
            $modelo['per_retira'] = $this->per_retira_id;
            $modelo['cliente'] = $this->cliente_id;
            $modelo['tarea'] = $this->tarea_id;
            $modelo['sucursal'] = $this->sucursal_id;
            $modelo['estado'] = $this->estado_id;
            $modelo['observacion_bodega'] = $this->observacion_bodega;
            $modelo['proyecto'] = $this->proyecto_id;
            $modelo['etapa'] = $this->etapa_id;
        }



        return $modelo;
    }
}
