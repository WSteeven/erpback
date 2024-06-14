<?php

namespace App\Http\Resources\Vehiculos;

use Illuminate\Http\Resources\Json\JsonResource;
use Src\Shared\Utils;

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
            'vehiculo_id' => $this->vehiculo_id,
            'entrega' => $this->entrega->nombres . ' ' . $this->entrega->apellidos,
            'responsable' => $this->responsable->nombres . ' ' . $this->responsable->apellidos,
            'canton' => $this->canton->canton,
            'canton_id' => $this->canton_id,
            'responsable_id' => $this->responsable_id,
            'observacion_recibe' => $this->observacion_recibe,
            'observacion_entrega' => $this->observacion_entrega,
            'fecha_entrega' => $this->fecha_entrega,
            'estado' => $this->estado,
            'accesorios' =>  $this->accesorios ? Utils::convertirStringComasArray($this->accesorios) : null,
            'estado_carroceria' =>  $this->estado_carroceria ? Utils::convertirStringComasArray($this->estado_carroceria) : null,
            'estado_mecanico' =>  $this->estado_mecanico ? Utils::convertirStringComasArray($this->estado_mecanico) : null,
            'estado_electrico' =>  $this->estado_electrico ? Utils::convertirStringComasArray($this->estado_electrico) : null,
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
