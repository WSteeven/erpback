<?php

namespace App\Http\Resources;

use App\Models\PreingresoMaterial;
use Illuminate\Http\Resources\Json\JsonResource;

class PreingresoMaterialResource extends JsonResource
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
        $modelo  = [
            'id' => $this->id,
            'observacion' => $this->observacion,
            'cuadrilla' => $this->cuadrilla,
            'num_guia' => $this->num_guia,
            'courier' => $this->courier,
            'fecha' => date('d/m/Y', strtotime($this->fecha)),
            'tarea' => $this->tarea?->titulo,
            'cliente' => $this->cliente?->empresa?->razon_social,
            'autorizador' => $this->autorizador->nombres . ' ' . $this->autorizador->apellidos,
            'responsable' => $this->responsable->nombres . ' ' . $this->responsable->apellidos,
            'responsable_id' => $this->responsable_id,
            'coordinador' => $this->coordinador->nombres . ' ' . $this->coordinador->apellidos,
            'autorizacion' => $this->autorizacion->nombre,
            'observacion_aut' => $this->observacion_aut,
        ];

        if ($controller_method == 'show') {
            $modelo['tarea'] = $this->tarea_id;
            $modelo['cliente'] = $this->cliente_id;
            $modelo['autorizador'] = $this->autorizador_id;
            $modelo['coordinador'] = $this->coordinador_id;
            $modelo['autorizacion'] = $this->autorizacion_id;
            $modelo['listadoProductos'] = PreingresoMaterial::listadoProductos($this->id);
        }
        return $modelo;
    }
}
