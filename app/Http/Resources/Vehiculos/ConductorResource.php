<?php

namespace App\Http\Resources\Vehiculos;

use Illuminate\Http\Resources\Json\JsonResource;

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
            'id' => $this->id,
            'empleado' => $this->empleado->nombres . ' ' . $this->empleado->apellidos,
            'identificacion' => $this->empleado->identificacion,
            'tipo_licencia' => $this->tipo_licencia,
            'inicio_vigencia' => $this->inicio_vigencia,
            'fin_vigencia' => $this->fin_vigencia,
            'puntos' => $this->puntos,
            'plaza' => $this->plaza,
        ];

        if ($controller_method == 'show') {
            $modelo['empleado'] = $this->empleado_id;
        }

        return $modelo;
    }
}
