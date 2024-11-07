<?php

namespace App\Http\Resources\RecursosHumanos\NominaPrestamos;

use App\Models\Empleado;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SolicitudVacacionResource extends JsonResource
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
            'empleado' => Empleado::extraerNombresApellidos($this->empleado),
            'autorizador' => Empleado::extraerNombresApellidos($this->autorizador),
            'periodo' =>   $this->periodo->nombre,
            'dias_solicitados' =>  $this->dias_solicitados,
            'fecha_inicio' =>  $this->fecha_inicio,
            'fecha_fin' => $this->fecha_fin,
            'autorizacion' => $this->autorizacion->nombre,
            'reemplazo' => Empleado::extraerNombresApellidos($this->reemplazo),
        ];

        if($controller_method === 'show'){
            $modelo['empleado'] = $this->empleado_id;
            $modelo['autorizador'] = $this->autorizador_id;
            $modelo['autorizacion'] = $this->autorizacion_id;
//            $modelo['periodo'] = $this->periodo_id;
            $modelo['reemplazo'] = $this->reemplazo_id;
            $modelo['funciones'] = $this->funciones;
        }
        return $modelo;
    }
}
