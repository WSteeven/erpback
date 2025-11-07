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
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        $controller_method = $request->route()->getActionMethod();
        $empleados_activos = $this->empleados->where('estado', true);
        $modelo = [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'vehiculo' => $this->vehiculo_id,
            'placa' => $this->vehiculo->placa,
            'nombre_alternativo' => $this->nombre_alternativo ?? 'No configurado',
            'region' => $this->region,
            'activo' => $this->activo,
            'coordinador' => $this->coordinador ? Empleado::extraerNombresApellidos($this->coordinador) : null,
            'coordinador_id' => $this->coordinador_id,
            'cant_empleados' => count($empleados_activos),
        ];

        if ($controller_method == 'show') {
            $modelo['coordinador'] = $this->coordinador_id;
            $modelo['vehiculo'] = $this->vehiculo_id;
            $modelo['nombre_alternativo'] = $this->nombre_alternativo;
            $modelo['empleados'] = EmpleadoLiteResource::collection($empleados_activos);
        }

        return $modelo;
    }
}
