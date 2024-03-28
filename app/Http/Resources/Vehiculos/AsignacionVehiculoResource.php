<?php

namespace App\Http\Resources\Vehiculos;

use Illuminate\Http\Resources\Json\JsonResource;

class AsignacionVehiculoResource extends JsonResource
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
            'vehiculo' => $this->vehiculo->placa,
            'entrega' => $this->entrega->empleado->nombres . ' ' . $this->entrega->empleado->apellidos,
            'responsable' => $this->responsable->empleado->nombres . ' ' . $this->responsable->empleado->apellidos,
            'canton' => $this->canton->canton,
            'responsable_id' => $this->responsable_id,
            'observacion_recibe' => $this->observacion_recibe,
            'observacion_entrega' => $this->observacion_entrega,
            'fecha_entrega' => $this->fecha_entrega,
            'estado' => $this->estado,
        ];

        if ($controller_method == 'show') {
            $modelo['vehiculo'] = $this->vehiculo_id;
            $modelo['entrega'] = $this->entrega_id;
            $modelo['responsable'] = $this->responsable_id;
            $modelo['canton'] = $this->canton_id;
        }
        return $modelo;
    }
}
