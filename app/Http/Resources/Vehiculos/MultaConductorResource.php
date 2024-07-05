<?php

namespace App\Http\Resources\Vehiculos;

use Illuminate\Http\Resources\Json\JsonResource;
use Src\Shared\Utils;

class MultaConductorResource extends JsonResource
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
            'empleado' => $this->conductor->empleado->nombres . ' ' . $this->conductor->empleado->apellidos,
            'empleado_id' => $this->empleado_id,
            'fecha_infraccion' => date(Utils::MASKFECHA, strtotime($this->fecha_infraccion)),
            'placa' => $this->placa,
            'puntos' => $this->puntos,
            'total' => $this->total,
            'estado' => $this->estado,
            'descontable' => $this->descontable,
            'fecha_pago' => $this->fecha_pago ? date(Utils::MASKFECHA, strtotime($this->fecha_pago)) : null,
            'comentario'  => $this->comentario
        ];

        if ($controller_method == 'show') {
            $modelo['empleado'] = $this->empleado_id;
        }

        return $modelo;
    }
}
