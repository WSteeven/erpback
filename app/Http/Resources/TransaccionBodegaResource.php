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

        Log::channel('testing')->info('Log', ['detalles recibidos?:', $detalles]);

        $modelo = [
            'id'=>$this->id,
            'autorizacion'=>is_null($autorizacion)?'N/A':$autorizacion->nombre,
            'obs_autorizacion'=>is_null($autorizacion->pivot->observacion)?'N/A':$autorizacion->pivot->observacion,
            'justificacion'=>$this->justificacion,
            'fecha_limite'=>is_null($this->fecha_limite)?'N/A': $this->fecha_limite,
            'estado'=>is_null($estado)?'N/A':$estado->nombre,
            'obs_estado'=>is_null($estado->pivot->observacion)?'N/A':$estado->pivot->observacion,
            'solicitante'=>$this->solicitante->nombres.' '.$this->solicitante->apellidos,
            'tipo'=>$this->subtipo->tipoTransaccion->nombre,
            'subtipo'=>$this->subtipo->nombre,
            'sucursal'=>$this->sucursal->lugar,
            'autoriza'=>$this->autoriza->nombres.' '.$this->autoriza->apellidos,
            'lugar_destino'=>$this->lugar_destino,
            'atiende'=>is_null($this->atiende)?'':$this->atiende->nombres.' '.$this->atiende->apellidos,
            'created_at'=>$this->created_at,
        ];

        if($controller_method=='show'){
            // $modelo['autorizacion']=$this->autorizaciones()->first()->nombre;
            $modelo['autorizacion']=$autorizacion->id;
            $modelo['obs_autorizacion']=$autorizacion->pivot->observacion;
            // $modelo['estado']=$this->estados()->first()->nombre;
            $modelo['estado']=$estado->id;
            $modelo['obs_estado']=$estado->pivot->observacion;
            $modelo['solicitante_id']=$this->solicitante_id;
            $modelo['tipo']=$this->subtipo->tipoTransaccion->id;
            $modelo['subtipo']=$this->subtipo_id;
            $modelo['sucursal']=$this->sucursal_id;
            $modelo['per_autoriza_id']=$this->solicitante_id;
            $modelo['per_atiende_id']=$this->solicitante_id;
            $modelo['created_at']=date('d/m/Y', strtotime($this->created_at));
            $modelo['listadoProductosSeleccionados']= $detalles;
        }

        return $modelo;
    }
}
