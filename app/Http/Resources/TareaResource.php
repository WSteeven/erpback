<?php

namespace App\Http\Resources;

use App\Models\Canton;
use App\Models\Provincia;
use App\Models\UbicacionTarea;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;

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
            'titulo' => $this->titulo,
            'para_cliente_proyecto' => $this->para_cliente_proyecto,
            'proyecto' => $this->proyecto?->codigo_proyecto,
            'fiscalizador' => $this->fiscalizador?->nombres . ' ' . $this->fiscalizador?->apellidos,
            'coordinador' => $this->coordinador?->nombres . ' ' . $this->coordinador?->apellidos,
            'cliente' => $this->obtenerCliente(),
            'cliente_id' => $this->cliente_id,
            'cliente_final' => $this->clienteFinal ? $this->clienteFinal?->nombres . ' ' . $this->clienteFinal?->apellidos : null,
            'cantidad_trabajos' => $this->trabajos->count(),
            'medio_notificacion' => $this->medio_notificacion,
        ];

        if ($controller_method == 'show') {
            $modelo['cliente_final'] = $this->cliente_final_id;
            $modelo['fiscalizador'] = $this->fiscalizador_id;
            $modelo['coordinador'] = $this->coordinador_id;
            $modelo['proyecto'] = $this->proyecto_id;
            $modelo['cliente'] = $this->cliente_id;
        }

        return $modelo;
    }

    private function obtenerCliente()
    {
        if ($this->proyecto) {
            return $this->proyecto->cliente?->empresa?->razon_social;
        } else {
            return $this->cliente?->empresa?->razon_social;
        }
    }
}
