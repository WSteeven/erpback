<?php

namespace App\Http\Resources;

use App\Http\Resources\Tareas\EtapaResource;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Exception;

class ProyectoResource extends BaseResource
{
    /**
     * @throws Exception
     */
    protected function construirModelo()
    {
        $modelo = [
            'id' => $this->id,
            'codigo_proyecto' => $this->codigo_proyecto,
            'nombre' => $this->nombre,
            'fecha_inicio' => $this->fecha_inicio,
            'fecha_fin' => $this->fecha_fin,
            'fecha_hora_finalizado' => $this->fecha_hora_finalizado,
            'coordinador_id' => $this->coordinador_id,
            'coordinador' => $this->coordinador?->nombres . ' ' . $this->coordinador?->apellidos,
            'fiscalizador' => $this->fiscalizador?->nombres . ' ' . $this->fiscalizador?->apellidos,
            'cliente' => $this->cliente?->empresa->razon_social,
            'cliente_id' => $this->cliente_id,
            'canton' => $this->canton?->canton,
            'costo' => $this->costo,
            'tiempo_ocupado' => $this->calcularTiempoOcupado(),
            'finalizado' => $this->finalizado,
            'etapas' => EtapaResource::collection($this->etapas),
            'created_at' => Carbon::parse($this->created_at)->format('Y-m-d H:i:s'),
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

    /**
     * @throws Exception
     */
    private function calcularTiempoOcupado()
    {
        return $this->fecha_hora_finalizado ? CarbonInterval::seconds(Carbon::parse($this->fecha_hora_finalizado)->diffInSeconds(Carbon::parse($this->created_at)))->cascade()->forHumans() : null;
    }
}
