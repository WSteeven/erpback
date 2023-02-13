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
            'detalle' => $this->detalle,
            'destino' => $this->destino,
            'cliente' => $this->cliente?->id,
            'proyecto' => $this->proyecto?->codigo_proyecto,
            'supervisor' => $this->supervisor?->nombres . ' ' . $this->supervisor?->apellidos,
            'coordinador' => $this->coordinador?->nombres . ' ' . $this->coordinador?->apellidos,
            'cliente' => $this->obtenerCliente(),//$this->cliente?->empresa?->razon_social,
            'cliente_final' => $this->cliente_final ? $this->clienteFinal?->nombres . ' ' . $this->clienteFinal?->apellidos : null,
            'estado' => $this->subtareas()->where('fecha_hora_asignacion', '!=', null)->orderBy('fecha_hora_asignacion', 'asc')->first()?->estado,
            'observacion' => $this->observacion,
            'tiene_subtareas' => $this->tiene_subtareas,
            'cantidad_subtareas' => $this->subtareas->count(),
        ];

        if ($controller_method == 'show') {
            $modelo['cliente'] = $this->cliente_id;
            $modelo['cliente_final'] = $this->cliente_final_id;
            $modelo['coordinador'] = $this->coordinador_id;
            $modelo['supervisor'] = $this->supervisor_id;
            $modelo['proyecto'] = $this->proyecto_id;
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
