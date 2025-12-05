<?php

namespace App\Http\Resources\RecursosHumanos\ControlPersonal;

use App\Models\Empleado;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HorarioEmpleadoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        $controllerMethod = $request->route()->getActionMethod();
        $modelo = [
            'id' => $this->id,
            'empleado' => Empleado::extraerNombresApellidos($this->empleado),
            'empleado_id' => $this->empleado_id,
            'horario' => $this->horarioLaboral->nombre,
            'fecha_inicio' => $this->fecha_inicio,
            'fecha_fin' => $this->fecha_fin ?? 'Indefinido',
            'activo' => $this->activo,
        ];

        if ($controllerMethod == 'show') {
            $modelo['empleado'] = $this->empleado_id;
            $modelo['horario'] = $this->horario_id;
            $modelo['fecha_fin'] = $this->fecha_fin;
        }

        return $modelo;
    }
}
