<?php

namespace App\Http\Resources\Intranet;

use App\Models\Empleado;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrganigramaResource extends JsonResource
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

        $modelo = [
            'id' => $this->id,
            'empleado_id' => $this->empleado_id,
            'empleado' => Empleado::extraerNombresApellidos($this->empleado), // Obtener nombres y apellidos del empleado
            'cargo' => $this->cargo,
            'jefe_id' => $this->jefe_id,
            'jefe' => $this->jefe ? $this->jefe->empleado->nombres . ' ' . $this->jefe->empleado->apellidos : null, // Obtener nombres y apellidos del jefe inmediato
            'departamento' => $this->departamento,
            'nivel' => $this->nivel,
            'tipo' => $this->tipo,
            'created_at' => date('d/m/Y H:i:s', strtotime($this->created_at)),
            'updated_at' => date('d/m/Y H:i:s', strtotime($this->updated_at)),
        ];

        return $modelo;
    }
}
