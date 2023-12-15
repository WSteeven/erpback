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
            'titulo' => $this->titulo,
            'proyecto_id' => $this->proyecto_id,
            'coordinador' => $this->coordinador ? Empleado::extraerNombresApellidos($this->coordinador) : null,
            'cliente_id' => $this->cliente_id,
            'cantidad_subtareas' => $this->subtareas->count(),
            'created_at' => Carbon::parse($this->created_at)->format('d-m-Y H:i:s'),
            'imagen_informe' => $this->imagen_informe ? url($this->imagen_informe) : null,
            'finalizado' => $this->finalizado,
            'metraje_tendido' => $this->metraje_tendido,
        ];

        if ($controller_method == 'show') {
            $modelo['cliente_final'] = $this->cliente_final_id;
            $modelo['fiscalizador'] = $this->fiscalizador_id;
            $modelo['coordinador'] = $this->coordinador_id;
            $modelo['proyecto'] = $this->proyecto_id;
            $modelo['cliente'] = $this->cliente_id;
            $modelo['ruta_tarea'] = $this->ruta_tarea_id;
            $modelo['para_cliente_proyecto'] = $this->para_cliente_proyecto;
            $modelo['ubicacion_trabajo'] = $this->ubicacion_trabajo;
            $modelo['observacion'] = $this->observacion;
            $modelo['fecha_solicitud'] = $this->fecha_solicitud;
            $modelo['etapa'] = $this->etapa_id;
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
