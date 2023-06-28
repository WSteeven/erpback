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
            'tipo_permiso' => $this->tipo_permiso_id,
            'tipo_permiso_info' =>$this->tipo_permiso_info!=null?$this->tipo_permiso_info->nombre:'',
            'fecha_inicio' =>  $this->cambiar_fecha($this->fecha_inicio),
            'fecha_fin' =>  $this->cambiar_fecha($this->fecha_fin),
            'justificacion' => $this->justificacion,
            'estado' => $this->estado_permiso_id,
            'estado_permiso_info' => $this->estado_permiso_info->nombre,
            'empleado' => $this->empleado_id,
            'empleado_info' => $this->empleado_info->nombres.' '.$this->empleado_info->apellidos,
            'nombre' =>$this->documento!= null?json_decode($this->documento)->nombre:'',
            'ruta' =>$this->documento?url(json_decode($this->documento)->ruta):null,
            'tamanio_bytes' =>$this->documento!= null?json_decode($this->documento)->tamanio_bytes:0,
        ];
        return $modelo;
    }
   private function cambiar_fecha($fecha){
    $fecha_formateada = Carbon::parse( $fecha)->format('d-m-Y');
        return $fecha_formateada;
    }
}
