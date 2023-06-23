<?php

namespace App\Http\Resources\RecursosHumanos\NominaPrestamos;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;

class PermisoEmpleadoResource extends JsonResource
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
            'motivo' => $this->motivo_id,
            'motivo_info' => $this->motivo_info->nombre,
            'fecha_inicio' =>  $this->cambiar_fecha($this->fecha_inicio),
            'fecha_fin' =>  $this->cambiar_fecha($this->fecha_fin),
            'justificacion' => $this->justificacion?url($this->justificacion):null ,
            'estado' => $this->estado_permiso_id,
            'estado_permiso_info' => $this->estado_permiso_info->nombre,
            'empleado' => $this->empleado_id,
            'empleado_info' => $this->empleado_info->nombres.' '.$this->empleado_info->apellidos,
        ];
        return $modelo;
    }
   private function cambiar_fecha($fecha){
    $fecha_formateada = Carbon::parse( $fecha)->format('d-m-Y');
        return $fecha_formateada;
    }
}
