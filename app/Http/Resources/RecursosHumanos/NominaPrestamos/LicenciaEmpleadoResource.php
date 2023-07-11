<?php

namespace App\Http\Resources\RecursosHumanos\NominaPrestamos;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class LicenciaEmpleadoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $modelo = [
            'id' => $this->id,
            'empleado' => $this->empleado,
            'empleado_info' => $this->empleado_info,
            'tipo_licencia' => $this->id_tipo_licencia,
            'estado' => $this->estado,
            'estado_info' => $this->estado_info,
            'fecha_inicio' => $this->cambiar_fecha($this->fecha_inicio),
            'fecha_fin' =>  $this->cambiar_fecha($this->fecha_fin),
            'justificacion' => $this->justificacion,
            'id_jefe_inmediato' => $this->empleado_info->jefe->id,
            'jefe_inmediato' =>  $this->empleado_info->jefe->nombres . '' . $this->empleado_info->jefe->apellidos,
        ];
        return $modelo;
    }
    private function cambiar_fecha($fecha)
    {
        $fecha_formateada = Carbon::parse($fecha)->format('d-m-Y');
        return $fecha_formateada;
    }
}
