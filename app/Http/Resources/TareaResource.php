<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TareaResource extends JsonResource
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
            'id' => $this->id,
            'codigo_tarea_jp' => $this->codigo_tarea_jp,
            'codigo_tarea_cliente' => $this->codigo_tarea_cliente,
            'fecha_solicitud' => $this->fecha_solicitud,
            'fecha_inicio' => $this->fecha_inicio,
            'fecha_finalizacion' => $this->fecha_finalizacion,
            'solicitante' => $this->solicitante,
            'correo_solicitante' => $this->correo_solicitante,
            'detalle' => $this->detalle,
            'es_proyecto' => $this->es_proyecto,
            'codigo_proyecto' => $this->codigo_proyecto,
            'cliente' => $this->cliente->empresa->razon_social,
            'coordinador' => $this->coordinador->nombres . ' ' . $this->coordinador->apellidos,
        ];

        if ($controller_method == 'show') {
            $modelo['cliente'] = $this->cliente_id;
        }

        return $modelo;
    }
}
