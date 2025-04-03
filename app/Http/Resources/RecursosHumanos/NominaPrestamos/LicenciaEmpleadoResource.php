<?php

namespace App\Http\Resources\RecursosHumanos\NominaPrestamos;

use App\Models\Empleado;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LicenciaEmpleadoResource extends JsonResource
{
//    private int $id_wellington =117;
//    private int $id_veronica_valencia=155;
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $jefe_id = $this->empleado_info->jefe_id;
//        if($jefe_id == $this->id_wellington) $jefe_id= $this->id_veronica_valencia;
        return [
            'id' => $this->id,
            'empleado' => $this->empleado,
            'empleado_info' => $this->empleado_info?->nombres . ' ' . $this->empleado_info?->apellidos,
            'tipo_licencia' => $this->id_tipo_licencia,
            'estado' => $this->estado,
            'estado_info' => $this->estado_info,
            'fecha_inicio' => Carbon::parse($this->fecha_inicio)->format('Y-m-d'),
            'dias_licencia' => $this->calcular_dias($this->fecha_inicio, $this->fecha_fin),
            'fecha_fin' =>  Carbon::parse($this->fecha_fin)->format('Y-m-d'),
            'justificacion' => $this->justificacion,
            'id_jefe_inmediato' => $jefe_id,
            'jefe_inmediato' =>  Empleado::extraerNombresApellidos(Empleado::find($jefe_id)),
        ];
    }

    private function calcular_dias($fecha_inicio, $fecha_fin)
    {
        // Suponiendo que $this->fecha_inicio y $this->fecha_fin son objetos Carbon
        $fechaInicio = Carbon::parse($fecha_inicio);
        $fechaFin = Carbon::parse($fecha_fin);
        // Calcular la diferencia en días
        return  $fechaInicio->diffInDays($fechaFin) + 1;
    }
}
