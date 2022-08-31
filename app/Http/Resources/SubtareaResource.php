<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SubtareaResource extends JsonResource
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
            'codigo_subtarea' => $this->codigo_subtarea,
            'detalle' => $this->detalle,
            'actividad_realizada' => $this->actividad_realizada,
            'novedades' => $this->novedades,
            'fiscalizador' => $this->fiscalizador,
            'ing_soporte' => $this->ing_soporte,
            'ing_instalacion' => $this->ing_instalacion,
            'tipo_instalacion' => $this->tipo_instalacion,
            'id_servicio' => $this->id_servicio,
            'ticket_phoenix' => $this->ticket_phoenix,
            'tipo_tarea' => $this->tipo_tarea->nombre,
            'tarea' => $this->tarea->codigo_tarea_jp,
            'coordinador' => $this->tarea->coordinador->nombres . ' ' . $this->tarea->coordinador->apellidos,
        ];

        if ($controller_method == 'show') {
            $modelo['cliente'] = $this->cliente_id;
            $modelo['tipo_tarea'] = $this->tipo_tarea_id;
            $modelo['tarea'] = $this->tarea_id;
        }

        return $modelo;
    }
}
