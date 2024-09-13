<?php

namespace App\Http\Resources\RecursosHumanos\NominaPrestamos;

use App\Models\Empleado;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VacacionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $controller_method = $request->route()->getActionMethod();
        $modelo = [
            'id' => $this->id,
            'empleado' => $this->empleado_id,
            'empleado_info' => $this->empleado_info != null ? $this->empleado_info->nombres . ' ' . $this->empleado_info->apellidos : '',
            'id_jefe_inmediato' => $this->empleado_info->jefe->id,
            'fecha_inicio' =>  $this->fecha_inicio,
            'fecha_fin' => $this->fecha_fin,
            'numero_dias' => $this->calcular_dias($this->fecha_inicio,$this->fecha_fin),
            'fecha_inicio_rango1_vacaciones' =>$this->fecha_inicio_rango1_vacaciones ? Carbon::parse($this->fecha_inicio_rango1_vacaciones)->format('Y-m-d'):null,
            'fecha_fin_rango1_vacaciones' =>  $this->fecha_fin_rango1_vacaciones? Carbon::parse($this->fecha_fin_rango1_vacaciones)->format('Y-m-d'):null,
            'numero_dias_rango1' => $this->calcular_dias($this->fecha_inicio_rango1_vacaciones,$this->fecha_fin_rango1_vacaciones),
            'fecha_inicio_rango2_vacaciones' => $this->fecha_inicio_rango2_vacaciones? Carbon::parse($this->fecha_inicio_rango2_vacaciones)->format('Y-m-d'):null,
            'fecha_fin_rango2_vacaciones' =>   $this->fecha_fin_rango2_vacaciones?Carbon::parse($this->fecha_fin_rango2_vacaciones)->format('Y-m-d'):null,
            'numero_dias_rango2' => $this->calcular_dias($this->fecha_inicio_rango2_vacaciones,$this->fecha_fin_rango2_vacaciones),
            'periodo' =>   $this->periodo_id,
            'periodo_info' => $this->periodo_info? $this->periodo_info->nombre:'' ,
            'solicitud' =>   $this->solicitud,
            'estado' => $this->estado,
            'estado_permiso_info' => $this->estado_permiso_info ?$this->estado_permiso_info->nombre:'',
            'numero_rangos' => $this->numero_rangos,
            'reemplazo' => Empleado::extraerNombresApellidos($this->reemplazo)
        ];

        if($controller_method === 'show'){
            $modelo['reemplazo'] = $this->reemplazo_id;
            $modelo['funciones'] = $this->funciones;
        }
        return $modelo;
    }

    private function calcular_dias($fecha_inicio, $fecha_fin)
    {
        $fechaInicio = Carbon::parse($fecha_inicio);
        $fechaFin = Carbon::parse($fecha_fin);
        // Calcular la diferencia en días
        return  $fechaInicio->diffInDays($fechaFin);
    }
}
