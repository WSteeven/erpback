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
        $autorizacion = TransaccionBodega::ultimaAutorizacion($this->id);
        $estado = TransaccionBodega::ultimoEstado($this->id);
        $detalles = TransaccionBodega::listadoProductos($this->id);

        // Log::channel('testing')->info('Log', ['controller method?:', $controller_method]);

        $modelo = [
            'id' => $this->id,
            'autorizacion' => is_null($autorizacion) ? 'N/A' : $autorizacion->nombre,
            'obs_autorizacion' => is_null($autorizacion) ? 'N/A' : $autorizacion->pivot->observacion,
            'justificacion' => $this->justificacion,
            'comprobante' => $this->comprobante,
            'fecha_limite' => !is_null($this->fecha_limite) ?? $this->fecha_limite,
            'estado' => is_null($estado) ? 'N/A' : $estado->nombre,
            'obs_estado' => is_null($estado->pivot->observacion) ? 'N/A' : $estado->pivot->observacion,
            'solicitante' => $this->solicitante ? $this->solicitante->nombres . ' ' . $this->solicitante->apellidos : 'N/A',
            'solicitante_id' => $this->solicitante_id,
            'tipo' => $this->tipo?->nombre,
            'motivo' => $this->motivo?->nombre,
            'sucursal' => $this->sucursal->lugar,
            'per_autoriza' => $this->autoriza->nombres . ' ' . $this->autoriza->apellidos,
            'per_atiende' => is_null($this->atiende) ? '' : $this->atiende->nombres . ' ' . $this->atiende->apellidos,
            'per_retira'=>is_null($this->retira)?'':$this->retira->nombres . ' ' . $this->retira->apellidos,
            'tarea' => $this->tarea ? $this->tarea->detalle : null,
            'cliente' => $this->cliente ? $this->cliente->empresa->razon_social : null,
            'listadoProductosTransaccion' => $detalles,
            'created_at' => date('d/m/Y', strtotime($this->created_at)),

            //variables auxiliares
            'tiene_obs_autorizacion'=>is_null($autorizacion)?false:true,
            'tiene_obs_estado'=>is_null($estado)?false:true,
            // 'retira_tercero'=>$this->tarea?true:false,
            'es_tarea'=>$this->tarea?true:false,
        ];

        if ($controller_method == 'show') {
            $modelo['autorizacion'] = is_null($autorizacion)?'N/A':$autorizacion->id;
            $modelo['obs_autorizacion'] = is_null($autorizacion)?'N/A':$autorizacion->pivot->observacion;
            $modelo['estado'] = $estado->id;
            $modelo['obs_estado'] = $estado->pivot->observacion;
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
