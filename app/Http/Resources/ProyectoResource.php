<?php

namespace App\Http\Resources;

use App\Http\Resources\Tareas\EtapaResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ProyectoResource extends JsonResource
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
            'codigo_proyecto' => $this->codigo_proyecto,
            'nombre' => $this->nombre,
            'fecha_inicio' => $this->fecha_inicio,
            'fecha_fin' => $this->fecha_fin,
            'coordinador_id' => $this->coordinador_id,
            'coordinador' => $this->coordinador?->nombres . ' ' . $this->coordinador?->apellidos,
            'fiscalizador' => $this->fiscalizador?->nombres . ' ' . $this->fiscalizador?->apellidos,
            'cliente' => $this->cliente?->empresa->razon_social,
            'canton' => $this->canton?->canton,
            'costo' => $this->costo,
            'demora' => '0 días',
            'finalizado' => $this->finalizado,
        ];

        if ($controller_method == 'show') {
            $modelo['cliente'] = $this->cliente_id;
            $modelo['coordinador'] = $this->coordinador_id;
            $modelo['canton'] = $this->canton_id;
            $modelo['etapas'] = EtapaResource::collection($this->etapas);
        }

        return $modelo;
    }
}
