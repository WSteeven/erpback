<?php

namespace App\Http\Resources;

use App\Http\Resources\RecursosHumanos\EmpleadoLiteResource;
use App\Models\Empleado;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DepartamentoResource extends JsonResource
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
            'activo' => $this->activo,
            'responsable' => $this->responsable ? Empleado::extraerNombresApellidos($this->responsable) : null,
            'responsable_id' => $this->responsable_id,
            'telefono' => $this->telefono,
            'correo' => $this->correo,
            'cant_empleados'=> count($empleados_activos),
        ];

        if ($controller_method == 'show') {
            $modelo['responsable'] = $this->responsable_id;
            $modelo['empleados'] = EmpleadoLiteResource::collection($empleados_activos);
        }

        return $modelo;
    }
}
