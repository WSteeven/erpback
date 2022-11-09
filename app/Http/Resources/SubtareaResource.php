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
            'es_ventana' => $this->es_ventana,
            'subtarea_dependiente' => $this->subtarea_dependiente,
            'tipo_instalacion' => $this->tipo_instalacion,
            'id_servicio' => $this->id_servicio,
            'hora_inicio_ventana' => $this->hora_inicio_ventana,
            'hora_fin_ventana' => $this->hora_fin_ventana,
            'tipo_trabajo' => $this->tipo_trabajo->nombre,
            'tarea' => $this->tarea->codigo_tarea,
            'tarea_id' => $this->tarea_id,
            'grupo' => $this->grupo->nombre,
            'coordinador' => $this->tarea->coordinador->nombres . ' ' . $this->tarea->coordinador->apellidos,
            'fecha_hora_creacion' => $this->fecha_hora_creacion,
            'fecha_hora_asignacion' => $this->fecha_hora_asignacion,
            'fecha_hora_inicio' => $this->fecha_hora_inicio,
            'fecha_hora_finalizacion' => $this->fecha_hora_finalizacion,
            'fecha_hora_realizado' => $this->fecha_hora_realizado,
            'fecha_hora_suspendido' => $this->fecha_hora_suspendido,
            'estado' => $this->estado,
            'tecnicos_grupo_principal' => $this->tecnicosPrincipales(explode(',', $this->tecnicos_grupo_principal)),
        ];

        if ($controller_method == 'show') {
            $modelo['cliente'] = $this->tarea->cliente_id;
            $modelo['tipo_trabajo'] = $this->tipo_trabajo_id;
            $modelo['tarea'] = $this->tarea_id;
            $modelo['grupo'] = $this->grupo_id;
        }

        return $modelo;
    }
}
