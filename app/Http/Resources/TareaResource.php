<?php

namespace App\Http\Resources;

use App\Models\Canton;
use App\Models\Empleado;
use App\Models\Provincia;
use App\Models\Tarea;
use App\Models\UbicacionTarea;
use Carbon\Carbon;
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
            // 'fecha_solicitud' => $this->fecha_solicitud,
            'titulo' => $this->titulo,
            // 'observacion' => $this->observacion,
            // 'novedad' => $this->novedad,
            // 'para_cliente_proyecto' => $this->para_cliente_proyecto,
            // 'ubicacion_trabajo' => $this->ubicacion_trabajo,
            // 'ruta_tarea' => $this->rutaTarea?->ruta,
            // 'proyecto' => $this->proyecto?->codigo_proyecto,
            // 'proyecto_id' => $this->proyecto_id,
            // 'fiscalizador' => $this->fiscalizador?->nombres . ' ' . $this->fiscalizador?->apellidos,
            'coordinador' => Empleado::extraerNombresApellidos($this->coordinador),
            // 'cliente' => $this->obtenerCliente(),
            // 'cliente_id' => $this->cliente_id,
            // 'cliente_final' => $this->clienteFinal ? $this->clienteFinal?->nombres . ' ' . $this->clienteFinal?->apellidos : null,
            'cantidad_subtareas' => $this->subtareas->count(), //$this->tiene_subtareas ? $this->subtareas->count() : null,
            // 'medio_notificacion' => $this->medio_notificacion,
            // 'canton' => $this->obtenerCanton(),
            'imagen_informe' => $this->imagen_informe ? url($this->imagen_informe) : null,
            'finalizado' => $this->finalizado,
        ];

        if ($controller_method == 'show') {
            $modelo['cliente_final'] = $this->cliente_final_id;
            $modelo['fiscalizador'] = $this->fiscalizador_id;
            $modelo['coordinador'] = $this->coordinador_id;
            $modelo['proyecto'] = $this->proyecto_id;
            $modelo['cliente'] = $this->cliente_id;
            $modelo['ruta_tarea'] = $this->ruta_tarea_id;
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

    private function extraerNombresApellidos($empleado)
    {
        if (!$empleado) return null;
        return $empleado->nombres . ' ' . $empleado->apellidos;
    }

    private function obtenerCanton()
    {
        if ($this->para_cliente_proyecto === Tarea::PARA_PROYECTO) {
            return $this->proyecto->canton?->canton;
        } else if ($this->para_cliente_proyecto === Tarea::PARA_CLIENTE_FINAL) {
            return $this->clienteFinal?->canton?->canton;
        }
    }
}
