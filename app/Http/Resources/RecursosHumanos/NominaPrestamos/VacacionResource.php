<?php

namespace App\Http\Resources\RecursosHumanos\NominaPrestamos;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class VacacionResource extends JsonResource
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
            'empleado' => $this->empleado_id,
            'empleado_info' => $this->empleado_info != null ? $this->empleado_info->nombres . ' ' . $this->empleado_info->apellidos : '',
            'id_jefe_inmediato' => $this->empleado_info->jefe->id,
            'fecha_inicio' =>  $this->fecha_inicio,
            'fecha_fin' => $this->fecha_fin,
            'numero_dias' => $this->calcular_dias($this->fecha_inicio,$this->fecha_fin),
            'fecha_inicio_rango1_vacaciones' =>$this->fecha_inicio_rango1_vacaciones ? $this->cambiar_fecha($this->fecha_inicio_rango1_vacaciones):null,//  $this->cambiar_fecha($this->fecha_inicio_rango1_vacaciones),
            'fecha_fin_rango1_vacaciones' =>  $this->fecha_fin_rango1_vacaciones? $this->cambiar_fecha($this->fecha_fin_rango1_vacaciones):null,
            'numero_dias_rango1' => $this->calcular_dias($this->fecha_inicio_rango1_vacaciones,$this->fecha_fin_rango1_vacaciones),
            'fecha_inicio_rango2_vacaciones' => $this->fecha_inicio_rango2_vacaciones? $this->cambiar_fecha($this->fecha_inicio_rango2_vacaciones):null,
            'fecha_fin_rango2_vacaciones' =>   $this->fecha_fin_rango2_vacaciones?$this->cambiar_fecha($this->fecha_fin_rango2_vacaciones):null,
            'numero_dias_rango2' => $this->calcular_dias($this->fecha_inicio_rango2_vacaciones,$this->fecha_fin_rango2_vacaciones),
            'periodo' =>   $this->periodo_id,
            'periodo_info' => $this->periodo_info? $this->periodo_info->nombre:'' ,
            'solicitud' =>   $this->solicitud,
            'estado' => $this->estado,
            'estado_permiso_info' => $this->estado_permiso_info ?$this->estado_permiso_info->nombre:'',
            'numero_rangos' => $this->numero_rangos,
        ];
        return $modelo;
    }
    private function cambiar_fecha($fecha)
    {
        $fecha_formateada = Carbon::parse($fecha)->format('d-m-Y');
        return $fecha_formateada;
    }
    private function calcular_dias($fecha_inicio, $fecha_fin)
    {
        $fechaInicio = Carbon::parse($fecha_inicio);
        $fechaFin = Carbon::parse($fecha_fin);
        // Calcular la diferencia en días
        return  $fechaInicio->diffInDays($fechaFin);
    }
}
