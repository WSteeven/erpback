<?php

namespace App\Http\Resources;

use App\Http\Resources\Tareas\EtapaResource;

class ProyectoResource extends BaseResource
{
    protected function construirModelo($campos)
    {
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
            'cliente_id' => $this->cliente_id,
            'canton' => $this->canton?->canton,
            'costo' => $this->costo,
            'demora' => '0 días',
            'finalizado' => $this->finalizado,
            'etapas' => EtapaResource::collection($this->etapas),
        ];

        // Lógica específica del método 'show'
        if ($this->controllerMethodIsShow()) {
            $modelo['cliente'] = $this->cliente_id;
            $modelo['coordinador'] = $this->coordinador_id;
            $modelo['canton'] = $this->canton_id;
            $modelo['etapas'] = EtapaResource::collection($this->etapas);
            $modelo['fiscalizador'] = $this->fiscalizador_id;
        }

        return $modelo;
    }
}
