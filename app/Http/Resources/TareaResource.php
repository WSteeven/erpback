<?php

namespace App\Http\Resources;

use App\Models\Canton;
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
        $primeraSubtarea = $this->subtareas?->first();

        $modelo = [
            'id' => $this->id,
            'codigo_tarea' => $this->codigo_tarea,
            'codigo_tarea_cliente' => $this->codigo_tarea_cliente,
            'fecha_solicitud' => $this->fecha_solicitud,
            'titulo' => $this->titulo,
            'observacion' => $this->observacion,
            'tiene_subtareas' => $this->tiene_subtareas,
            'para_cliente_proyecto' => $this->para_cliente_proyecto,
            'proyecto' => $this->proyecto?->codigo_proyecto,
            'proyecto_id' => $this->proyecto_id,
            'fiscalizador' => $this->fiscalizador?->nombres . ' ' . $this->fiscalizador?->apellidos,
            'coordinador' => $this->coordinador?->nombres . ' ' . $this->coordinador?->apellidos,
            'cliente' => $this->obtenerCliente(),
            'cliente_id' => $this->cliente_id,
            'cliente_final' => $this->clienteFinal ? $this->clienteFinal?->nombres . ' ' . $this->clienteFinal?->apellidos : null,
            'cantidad_subtareas' => $this->tiene_subtareas ? $this->subtareas->count() : null,
            'medio_notificacion' => $this->medio_notificacion,
            'canton' => $this->obtenerCanton(),
            'subtarea' => $primeraSubtarea,
            // Subtarea
             'estado' => $primeraSubtarea ? $primeraSubtarea->estado : null,
             'tipo_trabajo' => !$this->tiene_subtareas ? ($primeraSubtarea ? $primeraSubtarea->tipo_trabajo->descripcion : null) : null,
             'fecha_solicitud' => !$this->tiene_subtareas ? ($primeraSubtarea ? $primeraSubtarea->fecha_solicitud : null) : null,
             'es_ventana' => !$this->tiene_subtareas ? ($primeraSubtarea ? $primeraSubtarea->es_ventana : null) : null,
             'fecha_hora_creacion' => !$this->tiene_subtareas ? ($primeraSubtarea ? $primeraSubtarea->fecha_hora_creacion : null) : null,
             'fecha_inicio_trabajo' => !$this->tiene_subtareas ? ($primeraSubtarea ? Carbon::parse($primeraSubtarea->fecha_inicio_trabajo)->format('d-m-Y') : null) : null,
             'hora_inicio_trabajo' => !$this->tiene_subtareas ? ($primeraSubtarea ? $primeraSubtarea->hora_inicio_trabajo : null) : null,
             'hora_fin_trabajo' => !$this->tiene_subtareas ? ($primeraSubtarea ? $primeraSubtarea->hora_fin_trabajo : null) : null,
             'fecha_hora_asignacion' => !$this->tiene_subtareas ? ($primeraSubtarea ? $primeraSubtarea->fecha_hora_asignacion : null) : null,
             'fecha_hora_agendado' => !$this->tiene_subtareas ? ($primeraSubtarea ? $primeraSubtarea->fecha_hora_agendado : null) : null,
             'fecha_hora_ejecucion' => !$this->tiene_subtareas ? ($primeraSubtarea ? $primeraSubtarea->fecha_hora_ejecucion : null) : null,
             'fecha_hora_realizado' => !$this->tiene_subtareas ? ($primeraSubtarea ? $primeraSubtarea->fecha_hora_realizado : null) : null,
             'fecha_hora_finalizacion' => !$this->tiene_subtareas ? ($primeraSubtarea ? $primeraSubtarea->fecha_hora_finalizacion : null) : null,
             'fecha_hora_' => !$this->tiene_subtareas ? ($primeraSubtarea ? $primeraSubtarea->fecha_hora_finalizacion : null) : null,
             'grupo' => !$this->tiene_subtareas ? ($primeraSubtarea ? $primeraSubtarea->grupo?->nombre : null) : null,
             'empleado' => !$this->tiene_subtareas ? ($primeraSubtarea ? $this->extraerNombresApellidos($primeraSubtarea->empleado) : null) : null,
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

    private function extraerNombresApellidos($empleado)
    {
        if (!$empleado) return null;
        return $empleado->nombres . ' ' . $empleado->apellidos;
    }

    private function obtenerCanton()
    {
        if ($this->para_cliente_proyecto === Tarea::PARA_PROYECTO) {
            return $this->proyecto->canton->canton;
        } else if ($this->para_cliente_proyecto === Tarea::PARA_CLIENTE_FINAL) {
            return $this->clienteFinal->canton->canton;
        }
    }
}
