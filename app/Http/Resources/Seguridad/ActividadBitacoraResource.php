<?php

namespace App\Http\Resources\Seguridad;

use Illuminate\Http\Resources\Json\JsonResource;

class ActividadBitacoraResource extends JsonResource
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
            'id' => $this['id'],
            'fecha_hora_inicio' => $this['fecha_hora_inicio'],
            'fecha_hora_fin' => $this['fecha_hora_fin'],
            'notificacion_inmediata' => $this['notificacion_inmediata'] ? 'Sí' : 'No',
            'actividad' => $this['actividad'],
            'ubicacion' => $this['ubicacion'],
            'fotografia_evidencia_1' => $this['fotografia_evidencia_1'] ? url($this['fotografia_evidencia_1']) : null,
            'fotografia_evidencia_2' => $this['fotografia_evidencia_2'] ? url($this['fotografia_evidencia_2']) : null,
            'medio_notificacion' => $this['medio_notificacion'],
        ];

        if (in_array($controller_method, ['index', 'update', 'store'])) {
            $modelo['tipo_evento_bitacora'] = $this->tipoEventoBitacora->nombre;
            $modelo['tiene_adjuntos'] = $this->archivos()->exists();
        }

        if ($controller_method == 'show') {
            $modelo['tipo_evento_bitacora'] = $this['tipo_evento_bitacora_id'];
            $modelo['visitante'] = new VisitanteResource($this->visitante);
        }

        return $modelo;
    }
}
