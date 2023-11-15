<?php

namespace App\Http\Resources\Vehiculos;

use Illuminate\Http\Resources\Json\JsonResource;

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
            'fecha_infraccion' => date('d-m-Y', strtotime($this->fecha_infraccion)),
            'placa' => $this->placa,
            'puntos' => $this->puntos,
            'total' => $this->total,
            'estado' => $this->estado,
            'fecha_pago' => date('d-m-Y', strtotime($this->fecha_pago)),
            'comentario'  => $this->comentario
        ];

        if ($controller_method == 'show') {
            $modelo['empleado'] = $this->empleado_id;
        }

        return $modelo;
    }
}
