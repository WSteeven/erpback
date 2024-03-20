<?php

namespace App\Http\Resources\Vehiculos;

use App\Http\Resources\EmpleadoResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Src\Shared\Utils;

class ConductorResource extends JsonResource
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
            'id' => $this->empleado_id,
            'empleado' => $this->empleado->nombres . ' ' . $this->empleado->apellidos,
            'nombres' => $this->empleado->nombres,
            'apellidos' => $this->empleado->apellidos,
            'identificacion' => $this->empleado->identificacion,
            'tipo_licencia' => $this->tipo_licencia,
            'inicio_vigencia' => $this->inicio_vigencia,
            'fin_vigencia' => $this->fin_vigencia,
            'puntos' => $this->puntos,
            'plaza' => $this->plaza,
            'info_empleado'  => new EmpleadoResource($this->empleado),
            'multas' =>  $this->multas->count(),
        ];

        if ($controller_method == 'show') {
            $modelo['empleado'] = $this->empleado_id;
            $modelo['tipo_licencia'] = $this->tipo_licencia ? Utils::convertirStringComasArray($this->tipo_licencia) : null;
            $modelo['multas'] = MultaConductorResource::collection($this->multas);
        }

        return $modelo;
    }
}
