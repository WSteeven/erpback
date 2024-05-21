<?php

namespace App\Http\Resources\Vehiculos;

use Illuminate\Http\Resources\Json\JsonResource;
use Src\Shared\Utils;

class MantenimientoVehiculoResource extends JsonResource
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
            'vehiculo' => $this->vehiculo->placa,
            'servicio' => $this->servicio->nombre,
            'empleado' => $this->empleado->nombres . ' ' . $this->empleado->apellidos,
            'supervisor' => $this->supervisor->nombres . ' ' . $this->supervisor->apellidos,
            'fecha_realizado' => $this->fecha_realizado,
            'km_realizado' => $this->km_realizado,
            'imagen_evidencia' => $this->imagen_evidencia,
            'estado' => $this->estado,
            'km_retraso' => $this->km_retraso,
            'dias_postergado' => $this->dias_postergado,
            'motivo_postergacion' => $this->motivo_postergacion,
            'observacion' => $this->observacion,
            'created_at' => date(Utils::MASKFECHA, strtotime($this->created_at))
        ];

        if ($controller_method == 'show') {
            $modelo['vehiculo'] = $this->vehiculo_id;
            $modelo['servicio'] = $this->servicio_id;
            $modelo['empleado'] = $this->empleado_id;
            $modelo['supervisor'] = $this->supervisor_id;
        }

        return $modelo;
    }
}
