<?php

namespace App\Http\Resources;

use App\Models\TransaccionBodega;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;

class TransaccionBodegaResource extends JsonResource
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
        $detalles = TransaccionBodega::listadoProductos($this->id);

        // Log::channel('testing')->info('Log', ['controller method?:', $controller_method]);

        $modelo = [
            'id' => $this->id,
            'autorizacion' =>  $this->autorizacion?->nombre,
            'observacion_aut' =>  $this->observacion_aut,
            'justificacion' => $this->justificacion,
            'comprobante' => $this->comprobante,
            'fecha_limite' => $this->fecha_lximite,
            'estado' => $this->estado->nombre,
            'observacion_est' =>  $this->observacion_est,
            'solicitante' => $this->solicitante ? $this->solicitante->nombres . ' ' . $this->solicitante->apellidos : 'N/A',
            'devolucion' => $this->devolucion_id,
            'solicitante_id' => $this->solicitante_id,
            'tipo' => $this->tipo?->nombre,
            'motivo' => $this->motivo?->nombre,
            'sucursal' => $this->sucursal->lugar,
            'sucursal_id' => $this->sucursal_id,
            'per_autoriza' => $this->autoriza->nombres . ' ' . $this->autoriza->apellidos,
            'per_atiende' => is_null($this->atiende) ? '' : $this->atiende->nombres . ' ' . $this->atiende->apellidos,
            'per_retira'=>is_null($this->retira)?'':$this->retira->nombres . ' ' . $this->retira->apellidos,
            'per_retira_id'=>$this->retira?->id,
            'tarea' => $this->tarea ? $this->tarea->detalle : null,
            'cliente' => $this->cliente ? $this->cliente->empresa->razon_social : null,
            'cliente_id' => $this->cliente_id,
            'listadoProductosTransaccion' => $detalles,
            'created_at' => date('d/m/Y', strtotime($this->created_at)),

            //variables auxiliares
            'tiene_obs_autorizacion'=>is_null($this->autorizacion_id)?false:true,
            'tiene_obs_estado'=>is_null($this->estado_id)?false:true,
            // 'retira_tercero'=>$this->tarea?true:false,
            'es_tarea'=>$this->tarea?true:false,
            'es_transferencia'=>$this->transferencia_id?true:false,
        ];

        if ($controller_method == 'show') {
            $modelo['autorizacion'] = $this->autorizacion_id;
            $modelo['estado'] = $this->estado_id;
            $modelo['solicitante'] = $this->solicitante_id;
            // $modelo['solicitante_id'] = $this->solicitante_id;
            $modelo['tipo'] = $this->tipo_id;
            $modelo['motivo'] = $this->motivo_id;
            $modelo['tarea'] = $this->tarea_id;
            $modelo['cliente'] = $this->cliente_id;
            $modelo['sucursal'] = $this->sucursal_id;
            $modelo['per_autoriza'] = $this->per_autoriza_id;
            $modelo['per_atiende'] = $this->per_atiende_id;
            $modelo['per_retira'] = $this->per_retira_id;
            $modelo['created_at'] = date('d/m/Y', strtotime($this->created_at));
            $modelo['listadoProductosTransaccion'] = $detalles;
        }

        return $modelo;
    }
}
