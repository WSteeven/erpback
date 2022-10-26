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
            'codigo_tarea' => $this->codigo_tarea,
            'codigo_tarea_cliente' => $this->codigo_tarea_cliente,
            'fecha_solicitud' => $this->fecha_solicitud,
            'hora_solicitud' => $this->hora_solicitud,
            'detalle' => $this->detalle,
            'es_proyecto' => $this->es_proyecto,
            'codigo_proyecto' => $this->codigo_proyecto,
            'supervisor' => $this->supervisor->nombres . ' ' . $this->supervisor->apellidos,
            'cliente' => $this->cliente->empresa->razon_social,
            'cliente_final' => $this->clienteFinal->nombres . ' ' . $this->clienteFinal->apellidos,
            // 'coordinador' => $this->coordinador->nombres . ' ' . $this->coordinador->apellidos,
        ];

        if ($controller_method == 'show') {
            $modelo['cliente'] = $this->cliente_id;
            $modelo['cliente_final'] = $this->clienteFinal->id;
            $modelo['supervisor'] = $this->supervisor->id;
        }

        return $modelo;
    }
}
