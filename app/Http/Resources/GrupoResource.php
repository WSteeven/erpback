<?php

namespace App\Http\Resources;

use App\Http\Resources\RecursosHumanos\EmpleadoLiteResource;
use App\Models\Empleado;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GrupoResource extends JsonResource
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
        $empleados_activos = $this->empleados->where('estado', true);
        $modelo = [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'region' => $this->region,
            'activo' => $this->activo,
            'coordinador' => $this->coordinador ? Empleado::extraerNombresApellidos($this->coordinador) : null,
            'cant_empleados'=> count($empleados_activos),
        ];

        if ($controller_method == 'show') {
            $modelo['coordinador'] = $this->coordinador_id;
            $modelo['empleados'] = EmpleadoLiteResource::collection($empleados_activos);
        }

        return $modelo;
    }
}
