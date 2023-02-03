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
            'descripcion_completa' => $this->descripcion_completa,
            'actividad_realizada' => $this->actividad_realizada,
            'es_dependiente' => $this->es_dependiente,
            'fiscalizador' => $this->fiscalizador,
            'subtarea_dependiente' => $this->subtarea?->codigo_subtarea,
            'subtarea_dependiente_id' => $this->subtarea_dependiente,
            'tipo_instalacion' => $this->tipo_instalacion,
            'id_servicio' => $this->id_servicio,
            'es_ventana' => $this->es_ventana,
            'fecha_ventana' => $this->fecha_ventana,
            'hora_inicio_ventana' => $this->hora_inicio_ventana,
            'hora_fin_ventana' => $this->hora_fin_ventana,
            'tipo_trabajo' => $this->tipo_trabajo->descripcion,
            'tarea' => $this->tarea->codigo_tarea,
            'tarea_id' => $this->tarea_id,
            'grupos' => $this->extraerNombres($this->grupos),
            'empleados' => $this->extraerNombresApellidos($this->empleados),
            'coordinador' => $this->tarea->coordinador->nombres . ' ' . $this->tarea->coordinador->apellidos,
            'fecha_hora_creacion' => $this->fecha_hora_creacion,
            'fecha_hora_asignacion' => $this->fecha_hora_asignacion,
            'fecha_hora_ejecucion' => $this->fecha_hora_ejecucion,
            'fecha_hora_finalizacion' => $this->fecha_hora_finalizacion,
            'fecha_hora_realizado' => $this->fecha_hora_realizado,
            'fecha_hora_suspendido' => $this->fecha_hora_suspendido,
            'causa_suspencion' => $this->causa_suspencion,
            'fecha_hora_cancelacion' => $this->fecha_hora_cancelacion,
            'causa_cancelacion' => $this->causa_cancelacion,
            'cliente_final' => $this->tarea->cliente_final,
            'modo_asignacion_trabajo' => $this->modo_asignacion_trabajo,
            'estado' => $this->estado,
            // 'es_primera_asignacion' => $this->tarea->esPrimeraAsignacion($this->id),
        ];

        if ($controller_method == 'show') {
            $modelo['cliente'] = $this->tarea->cliente_id;
            $modelo['tipo_trabajo'] = $this->tipo_trabajo_id;
            $modelo['tarea'] = $this->tarea_id;
            $modelo['grupo'] = $this->grupo_id;
            $modelo['cliente_final'] = $this->tarea->cliente_final_id;
            // $modelo['ubicacion_tarea'] = $this->tarea->ubicacionTarea ? new UbicacionTareaResource($this->tarea->ubicacionTarea) : null;
        }

        return $modelo;
    }

    public function extraerNombres($listado)
    {
        $nombres = $listado->map(fn ($item) => $item->nombre)->toArray();
        return implode('; ', $nombres);
    }

    public function extraerNombresApellidos($listado)
    {
        $nombres = $listado->map(fn ($item) => $item->nombres . ' ' . $item->apellidos)->toArray();
        return implode('; ', $nombres);
    }
}
