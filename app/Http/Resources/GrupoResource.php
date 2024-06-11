<?php

namespace App\Http\Resources;

use App\Http\Resources\RecursosHumanos\EmpleadoLiteResource;
use App\Models\Empleado;
use Illuminate\Http\Resources\Json\JsonResource;

class GrupoResource extends JsonResource
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
            'nombre' => $this->nombre,
            'region' => $this->region,
            'activo' => $this->activo,
            'coordinador' => $this->coordinador ? Empleado::extraerNombresApellidos($this->coordinador) : null,
            'cant_empleados'=> count($this->empleados),
        ];

        if ($controller_method == 'show') {
            $modelo['coordinador'] = $this->coordinador_id;
            $modelo['empleados'] = EmpleadoLiteResource::collection($this->empleados);
        }

        return $modelo;
    }
}
