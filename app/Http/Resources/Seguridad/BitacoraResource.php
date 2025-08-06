<?php

namespace App\Http\Resources\Seguridad;

use App\Models\Empleado;
use Illuminate\Http\Resources\Json\JsonResource;

class BitacoraResource extends JsonResource
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
            /*             'fecha_hora_inicio_turno' => $this['fecha_hora_inicio_turno'],
            'fecha_hora_fin_turno' => $this['fecha_hora_fin_turno'], */
            'fecha_hora_inicio_turno' => $this['fecha_hora_inicio_turno']
                ? \Carbon\Carbon::parse($this['fecha_hora_inicio_turno'])->timezone('America/Guayaquil')->format('Y-m-d H:i:s')
                : null,

            'fecha_hora_fin_turno' => $this['fecha_hora_fin_turno']
                ? \Carbon\Carbon::parse($this['fecha_hora_fin_turno'])->timezone('America/Guayaquil')->format('Y-m-d H:i:s')
                : null,
            'jornada' => $this['jornada'],
            'observaciones' => $this['observaciones'],
            // Campos para la revision del supervisor
            'revisado_por_supervisor' => $this['revisado_por_supervisor'],
            'retroalimentacion_supervisor' => $this['retroalimentacion_supervisor'],

        ];

        if (in_array($controller_method, ['index', 'store', 'update'])) {
            $modelo['zona'] = $this->zona->nombre;
            $modelo['jornada'] = $this['jornada'];
            $modelo['agente_turno'] = Empleado::extraerNombresApellidos($this->agenteTurno);
            $modelo['protector'] = Empleado::extraerNombresApellidos($this->protector);
            $modelo['conductor'] = Empleado::extraerNombresApellidos($this->conductor);
        }

        if ($controller_method == 'show') {
            $modelo['zona'] = $this['zona_id'];
            $modelo['agente_turno'] = $this['agente_turno_id'];
            $modelo['protector'] = $this['protector_id'];
            $modelo['conductor'] = $this['conductor_id'];
            $modelo['nombres_agente_turno'] = Empleado::extraerNombresApellidos($this->agenteTurno);
            $modelo['nombres_protector'] = Empleado::extraerNombresApellidos($this->protector);
            $modelo['nombres_conductor'] = Empleado::extraerNombresApellidos($this->conductor);
            $modelo['prendas_recibidas'] = json_decode($this['prendas_recibidas_ids']);
        }

        return $modelo;
    }
}
