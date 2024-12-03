<?php

namespace App\Http\Resources;

use App\Models\Transferencia;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransferenciaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $controller_method = $request->route()->getActionMethod();
        $detalles = Transferencia::listadoProductos($this->id);
        $modelo = [
            'id' => $this->id,
            'justificacion' => $this->justificacion,
            'sucursal_salida' => $this->sucursalSalida->lugar,
            'sucursal_destino' => $this->sucursalDestino->lugar,
            'cliente' => $this->cliente ? $this->cliente->empresa->razon_social : null,
            'solicitante' => $this->solicitante->nombres.' '.$this->solicitante->apellidos,
            // 'solicitante_id' => $this->solicitante_id,
            'autorizacion' => $this->autorizacion->nombre,
            // 'autorizacion_id' => $this->autorizacion_id,
            'per_autoriza' => $this->autoriza->nombres.' '.$this->autoriza->apellidos,
            'recibida' => $this->recibida,
            'estado' => $this->estado,
            'observacion_aut' => $this->observacion_aut,
            'observacion_est' => $this->observacion_est,
            'listadoProductos' => $detalles,

        ];

        if ($controller_method == 'show') {
            $modelo['sucursal_salida']=$this->sucursal_salida_id;
            $modelo['sucursal_destino']=$this->sucursal_destino_id;
            $modelo['solicitante']=$this->solicitante_id;
            $modelo['autorizacion']=$this->autorizacion_id;
            $modelo['per_autoriza']=$this->per_autoriza_id;
            $modelo['cliente']=$this->cliente_id;

        }

        return $modelo;
    }
}
